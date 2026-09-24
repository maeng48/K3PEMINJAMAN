<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\K3Inspection;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BorrowingController extends Controller
{
    /**
     * Mengambil Access Token OAuth2 dari Firebase Service Account (HTTP v1 API)
     */
    private function getFirebaseAccessToken()
    {
        $credentialsPath = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase-credentials.json'));
        
        if (!file_exists($credentialsPath)) {
            Log::error('FCM Error: File credentials Firebase tidak ditemukan di path: ' . $credentialsPath);
            return null;
        }

        $json = json_decode(file_get_contents($credentialsPath), true);
        
        if (!isset($json['client_email']) || !isset($json['private_key'])) {
            Log::error('FCM Error: Format file JSON credentials Firebase tidak valid.');
            return null;
        }

        $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $now = time();
        $payload = base64_encode(json_encode([
            'iss' => $json['client_email'],
            'sub' => $json['client_email'],
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
        ]));

        $unsignedToken = $header . '.' . $payload;
        
        // Fix penanganan baris baru (\n) pada private key OpenSSL
        $privateKey = str_replace("\\n", "\n", $json['private_key']);
        
        if (!openssl_sign($unsignedToken, $signature, $privateKey, 'SHA256')) {
            Log::error('FCM Error: Gagal melakukan openssl_sign pada JWT token.');
            return null;
        }

        $jwt = $unsignedToken . '.' . base64_encode($signature);

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]);

        $accessToken = $response->json()['access_token'] ?? null;

        if (!$accessToken) {
            Log::error('FCM Error: Gagal mendapatkan Access Token dari Google OAuth2. Respon: ' . $response->body());
        }

        return $accessToken;
    }

    /**
     * Kirim Push Notification ke HP pengguna via Firebase HTTP v1 API
     */
    private function sendPushNotification($fcmToken, $title, $message, $status)
    {
        if (empty($fcmToken)) {
            Log::warning('FCM Warning: fcm_token pengguna kosong, notifikasi dibatalkan.');
            return false;
        }

        $accessToken = $this->getFirebaseAccessToken();
        $projectId = env('FIREBASE_PROJECT_ID', 'k3-peminjaman');

        if (!$accessToken) {
            Log::error('FCM Error: Access token Google tidak tersedia.');
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type'  => 'application/json',
        ])->post($url, [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $message,
                ],
                'data' => [
                    'status' => $status,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                ]
            ]
        ]);

        // Logging status balasan dari server Firebase
        Log::info("FCM Respon Google [HTTP {$response->status()}]: ", $response->json() ?? []);

        return $response->successful();
    }

    /**
     * Tampilan Dashboard Utama
     */
    public function dashboard()
    {
        $borrowings = Borrowing::orderBy('id', 'desc')->get();
        $k3Inspections = K3Inspection::orderBy('id', 'desc')->get();
        
        $stats = [
            'total_pending'  => Borrowing::where('status', 'PENDING')->orWhere('status', 'pending')->count(),
            'total_dipinjam' => Borrowing::where('status', 'DIPINJAM')->orWhere('status', 'dipinjam')->count(),
            'total_kembali'  => Borrowing::where('status', 'DIKEMBALIKAN')->orWhere('status', 'dikembalikan')->count(),
            'total_inspeksi' => K3Inspection::count(),
        ];
        
        return view('dashboard', compact('borrowings', 'k3Inspections', 'stats'));
    }

    /**
     * Endpoint JSON untuk Auto-Refresh Statistik Dashboard
     */
    public function getStatsJson()
    {
        $stats = [
            'total_pending'  => Borrowing::where('status', 'PENDING')->orWhere('status', 'pending')->count(),
            'total_dipinjam' => Borrowing::where('status', 'DIPINJAM')->orWhere('status', 'dipinjam')->count(),
            'total_kembali'  => Borrowing::where('status', 'DIKEMBALIKAN')->orWhere('status', 'dikembalikan')->count(),
            'total_inspeksi' => K3Inspection::count(),
        ];

        return response()->json($stats);
    }

    /**
     * Master Data APD & Aset
     */
    public function index()
    {
        $borrowings = Borrowing::orderBy('id', 'desc')->get();
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Halaman Khusus 1: Permohonan Peminjaman (Pending Approval)
     */
    public function indexPending()
    {
        $borrowings = Borrowing::where('status', 'pending')
                               ->orWhere('status', 'PENDING')
                               ->orderBy('id', 'desc')
                               ->get(); 

        return view('borrowings.pending', compact('borrowings'));
    }

    public function indexActive()
    {
        if (in_array(strtolower(auth()->user()->role ?? ''), ['tl', 'teamleader'])) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak: Team Leader hanya memiliki akses untuk Dokumen K3.');
        }
        $borrowings = Borrowing::where('status', 'DIPINJAM')->orWhere('status', 'dipinjam')->orderBy('id', 'desc')->get();
        $title = "Daftar Barang Sedang Dipinjam";
        return view('borrowings.filter_page', compact('borrowings', 'title'));
    }

    public function indexReturned()
    {
        if (in_array(strtolower(auth()->user()->role ?? ''), ['tl', 'teamleader'])) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak: Team Leader hanya memiliki akses untuk Dokumen K3.');
        }
        $borrowings = Borrowing::where('status', 'DIKEMBALIKAN')->orWhere('status', 'dikembalikan')->orderBy('id', 'desc')->get();
        $title = "Daftar Barang Sudah Dikembalikan";
        return view('borrowings.filter_page', compact('borrowings', 'title'));
    }

    /**
     * Halaman Riwayat Peminjaman yang Ditolak (Rejected)
     */
    public function indexRejected()
    {
        if (in_array(strtolower(auth()->user()->role ?? ''), ['tl', 'teamleader'])) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }
        $borrowings = Borrowing::where('status', 'REJECTED')->orWhere('status', 'rejected')->orderBy('id', 'desc')->get();
        $title = "Daftar Permohonan Ditolak";
        return view('borrowings.filter_page', compact('borrowings', 'title'));
    }

    /**
     * Setujui Peminjaman + Notifikasi HP
     */
    public function approve($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->update([
            'approval_status' => 'APPROVED',
            'status'          => 'DIPINJAM'
        ]);

        // Kirim Notifikasi FCM ke HP Peminjam
        $user = User::where('name', $borrowing->peminjam_nama)->first();
        if ($user && $user->fcm_token) {
            $this->sendPushNotification(
                $user->fcm_token,
                'Permohonan Disetujui! ✅',
                "Permohonan peminjaman barang '{$borrowing->merk_barang}' telah disetujui oleh Admin/PIC.",
                'APPROVED'
            );
        } else {
            Log::warning("FCM Warning: User '{$borrowing->peminjam_nama}' tidak ditemukan atau fcm_token bernilai NULL.");
        }

        return redirect()->back()->with('success', 'Permohonan peminjaman berhasil disetujui!');
    }

    /**
     * Tolak Peminjaman (Mendukung Alasan Penolakan) + Notifikasi HP
     */
    public function reject(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);
        
        $updateData = [
            'approval_status' => 'REJECTED',
            'status'          => 'DITOLAK'
        ];

        if ($request->has('catatan_penolakan')) {
            $updateData['catatan_penolakan'] = $request->catatan_penolakan;
        } elseif ($request->has('reason')) {
            $updateData['reason'] = $request->reason;
        }

        $borrowing->update($updateData);

        // Kirim Notifikasi FCM ke HP Peminjam
        $user = User::where('name', $borrowing->peminjam_nama)->first();
        if ($user && $user->fcm_token) {
            $this->sendPushNotification(
                $user->fcm_token,
                'Permohonan Ditolak ❌',
                "Mohon maaf, permohonan peminjaman barang '{$borrowing->merk_barang}' Anda ditolak.",
                'REJECTED'
            );
        } else {
            Log::warning("FCM Warning: User '{$borrowing->peminjam_nama}' tidak ditemukan atau fcm_token bernilai NULL.");
        }

        return redirect()->back()->with('success', 'Permohonan peminjaman berhasil ditolak!');
    }

    /**
     * Fungsi untuk menangani scan QR Code dari HP (Menampilkan Form)
     */
    public function scanQr($id)
    {
        $item = Borrowing::where('qr_code_id', $id)->orderBy('id', 'desc')->first();
        if (!$item && is_numeric($id)) {
            $item = Borrowing::find($id);
        }
        $qrCodeId = $item ? $item->qr_code_id : $id;

        $latest = Borrowing::where('qr_code_id', $qrCodeId)->orderBy('id', 'desc')->first();
        
        // 1. JIKA STATUS SUDAH DIKEMBALIKAN -> TAMPILKAN INFO SAJA TANPA FORM
        if ($latest && in_array($latest->status, ['DIKEMBALIKAN', 'dikembalikan'])) {
            return view('borrowings.form', [
                'item' => $latest,
                'isReturnedOnly' => true,
                'isPendingOnly' => false,
                'returnedMessage' => 'Barang dengan kode ' . $qrCodeId . ' sudah dikembalikan dan saat ini berstatus tersedia.'
            ]);
        }
        
        // 2. JIKA STATUS APPROVED / DIPINJAM -> Lari ke form pengembalian
        if ($latest && in_array($latest->approval_status, ['APPROVED', 'approved'])) {
            if (!request()->is('return*')) {
                return redirect()->route('borrowing.return.form', $latest->id);
            }
        }
        
        // 3. JIKA STATUS PENDING
        if ($latest && in_array($latest->approval_status, ['PENDING', 'pending'])) {
            return view('borrowings.form', [
                'item' => $latest,
                'isReturnedOnly' => false,
                'isPendingOnly' => true,
                'pendingMessage' => 'Barang dengan kode ' . $qrCodeId . ' sedang dalam status PENDING (Menunggu persetujuan Admin/PIC).'
            ]);
        }
        
        // 4. JIKA KOSONG / REJECTED -> Tampilkan Form Peminjaman
        $newItem = $latest ?? new Borrowing(['qr_code_id' => $qrCodeId]);
        return view('borrowings.form', [
            'item' => $newItem,
            'isReturnedOnly' => false,
            'isPendingOnly' => false
        ]);
    }

    public function storeBorrowing(Request $request, $id)
    {
        $request->validate([
            'peminjam_nama'   => 'required|string|max:255',
            'divisi'          => 'required|string|max:255',
            'kategori_barang' => 'required|string|max:255',
            'merk_barang'     => 'required|string|max:255',
            'keperluan'       => 'required|string',
            'foto'            => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('borrowings/pinjam', $filename, 'public');
            $fotoPath = 'storage/' . $path;
        }

        Borrowing::create([
            'qr_code_id'      => $id,
            'peminjam_nama'   => $request->peminjam_nama,
            'divisi'          => $request->divisi,
            'kategori_barang' => $request->kategori_barang,
            'merk_barang'     => $request->merk_barang,
            'keperluan'       => $request->keperluan,
            'catatan'         => $request->catatan,
            'tgl_pinjam'      => now(),
            'foto_pinjam'     => $fotoPath,
            'approval_status' => 'PENDING',
            'status'          => 'PENDING',
        ]);

        return redirect()->route('borrowing.scan', $id)->with('success', 'Permohonan peminjaman berhasil dikirim!');
    }

    public function showReturnForm($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $code = $borrowing->qr_code_id;
        
        if (in_array($borrowing->status, ['DIKEMBALIKAN', 'dikembalikan'])) {
            return view('borrowings.already_returned', [
                'code' => $code,
                'borrowing' => $borrowing,
                'message' => 'Barang dengan kode ' . $code . ' sudah dikembalikan sebelumnya. Terima kasih!'
            ]);
        }
        
        return view('borrowings.return_form', compact('code', 'borrowing'));
    }

    /**
     * Memproses penyimpanan foto pengembalian barang
     */
    public function processReturn(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $request->validate([
            'foto_kembali' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_kembali')) {
            $file = $request->file('foto_kembali');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('borrowings/kembali', $filename, 'public');
            $fotoPath = 'storage/' . $path;
        }

        $borrowing->update([
            'status'       => 'DIKEMBALIKAN',
            'tgl_kembali'  => now(),
            'foto_kembali' => $fotoPath,
        ]);

        return redirect()->route('borrowing.scan', $borrowing->qr_code_id)->with('success', 'Barang berhasil dikembalikan!');
    }

    /**
     * Generator Kode QR Otomatis (Format 3 digit: PLN-001, PLN-002, dst)
     */
    public function getNextCode()
    {
        $count = Borrowing::count();
        $nextNumber = $count + 1;
        $newCode = 'PLN-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        return response()->json(['next_code' => $newCode]);
    }

    /**
     * Menghapus Data Peminjaman (Destroy)
     */
    public function destroy($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        
        if ($borrowing->foto_pinjam && Storage::disk('public')->exists(str_replace('storage/', '', $borrowing->foto_pinjam))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $borrowing->foto_pinjam));
        }
        
        $fotoKembali = $borrowing->foto_kembali ?? $borrowing->return_photo ?? $borrowing->foto_pengembalian ?? null;
        if ($fotoKembali && Storage::disk('public')->exists(str_replace('storage/', '', $fotoKembali))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $fotoKembali));
        }

        $borrowing->delete();
        return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function getLatestBorrowings()
    {
        $borrowings = Borrowing::orderBy('id', 'desc')->get();
        return response()->json([
            'success' => true,
            'data'    => $borrowings
        ]);
    }

    /**
     * Penambahan Method checkStatus untuk Auto-Polling AJAX di HP
     */
    public function checkStatus($id)
    {
        $borrowing = Borrowing::find($id);

        if (!$borrowing && is_numeric($id)) {
            $borrowing = Borrowing::find($id);
        }

        if (!$borrowing) {
            $borrowing = Borrowing::where('qr_code_id', $id)->orderBy('id', 'desc')->first();
        }

        if (!$borrowing) {
            return response()->json([
                'status' => 'not_found',
                'approval_status' => 'NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'status'           => $borrowing->status,
            'approval_status'  => strtoupper($borrowing->approval_status ?? $borrowing->status),
            'id'               => $borrowing->id,
            'qr_code_id'       => $borrowing->qr_code_id,
            'catatan_penolakan'=> $borrowing->catatan_penolakan ?? $borrowing->reason ?? null
        ]);
    }

    public function checkSingleStatus($id)
    {
        $borrowing = Borrowing::find($id);

        if (!$borrowing) {
            return response()->json(['status' => 'not_found']);
        }

        return response()->json([
            'status'           => $borrowing->status,
            'approval_status'  => strtoupper($borrowing->approval_status ?? $borrowing->status),
            'return_url'       => route('borrowing.return.form', $borrowing->id),
            'id'               => $borrowing->id,
            'qr_code_id'       => $borrowing->qr_code_id,
            'catatan_penolakan'=> $borrowing->catatan_penolakan ?? $borrowing->reason ?? null
        ]);
    }
}