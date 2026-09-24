<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\K3Inspection;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class K3Controller extends Controller
{
    public function indexList(Request $request)
    {
        $role = strtolower(auth()->user()->role ?? '');
        $status = $request->query('status');
        $query = K3Inspection::query();

        // Filter dokumen berdasarkan parameter status dan peran
        if ($status === 'pending') {
            if ($role === 'tl') {
                $query->where(function($q) {
                    $q->whereNull('is_tl_signed')->orWhere('is_tl_signed', false);
                });
            } elseif ($role === 'manager') {
                $query->where(function($q) {
                    $q->whereNull('is_manager_signed')->orWhere('is_manager_signed', false);
                });
            }
        } elseif ($status === 'signed') {
            if ($role === 'tl') {
                $query->where('is_tl_signed', true);
            } elseif ($role === 'manager') {
                $query->where('is_manager_signed', true);
            }
        }

        $k3Inspections = $query->orderBy('id', 'desc')->get();
        $title = "Daftar Dokumen Laporan Inspeksi K3";
        return view('k3.index_list', compact('k3Inspections', 'title', 'status'));
    }

    public function form($id = null)
    {
        if (strtolower(auth()->user()->role ?? '') === 'admin') {
            return redirect()->route('k3.list')->with('error', 'Admin tidak memiliki hak akses untuk membuat atau mengedit dokumen K3.');
        }

        $k3 = $id ? K3Inspection::find($id) : null;
        $docNumber = $k3 ? $k3->doc_number : 'FR-K3L/ICON+/17-01';
        return view('k3.form', compact('k3', 'docNumber'));
    }

    public function save(Request $request)
    {
        if (strtolower(auth()->user()->role ?? '') === 'admin') {
            return redirect()->route('k3.list')->with('error', 'Akses ditolak. Admin tidak diizinkan membuat dokumen K3.');
        }

        $request->validate([
            'doc_number'    => 'required|string',
            'versi'         => 'required|string',
            'tgl_dokumen'   => 'required',
            'tahun'         => 'required',
            'nama_engineer' => 'required|string',
            'nama_tl'       => 'required|string',
            'nama_manager'  => 'required|string',
        ]);

        $data = [
            'doc_number'         => $request->doc_number,
            'versi'              => $request->versi,
            'tgl_dokumen'        => $request->tgl_dokumen,
            'tahun'              => $request->tahun,
            'nama_engineer'      => $request->nama_engineer,
            'nama_tl'            => $request->nama_tl,
            'nama_manager'       => $request->nama_manager,
            'checklist_data'     => json_encode($request->items ?? []),
            'is_engineer_signed' => true,
        ];

        if (!empty($request->signature_engineer)) {
            $path = $this->saveBase64Image($request->signature_engineer, 'engineer');
            if ($path) {
                $data['ttd_engineer'] = $path;
            }
        }

        if ($request->hasFile('foto_helmet')) {
            $data['foto_helmet'] = $request->file('foto_helmet')->store('apd_photos', 'public');
        }
        if ($request->hasFile('foto_harness')) {
            $data['foto_harness'] = $request->file('foto_harness')->store('apd_photos', 'public');
        }
        if ($request->hasFile('foto_vest_biru')) {
            $data['foto_vest_biru'] = $request->file('foto_vest_biru')->store('apd_photos', 'public');
        }
        if ($request->hasFile('foto_vest_merah')) {
            $data['foto_vest_merah'] = $request->file('foto_vest_merah')->store('apd_photos', 'public');
        }

        K3Inspection::create($data);

        return redirect()->route('k3.list')->with('success', 'Formulir K3 berhasil disimpan!');
    }

    public function showSignPage($id)
    {
        $k3 = K3Inspection::findOrFail($id);
        return view('k3.sign_preview', compact('k3'));
    }

    public function showManagerSignPage($id)
    {
        $k3 = K3Inspection::findOrFail($id);
        return view('k3.manager_sign_preview', compact('k3'));
    }

    public function processSignature(Request $request, $id)
    {
        $k3 = K3Inspection::findOrFail($id);
        $signAs = $request->input('sign_as'); 
        $signatureData = $request->input('signature_data');

        if (!$signatureData) {
            return back()->with('error', 'Tanda tangan belum dibuat. Silakan buat tanda tangan terlebih dahulu.');
        }

        $imageParts = explode(";base64,", $signatureData);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageByteArray = base64_decode($imageParts[1]);

        $fileName = 'k3_sign_' . $signAs . '_' . $id . '_' . time() . '.png';
        $filePath = 'k3_signatures/' . $fileName;

        Storage::disk('public')->put($filePath, $imageByteArray);

        $currentDate = now()->toDateString();

        if ($signAs === 'manager') {
            $k3->update([
                'ttd_manager'             => $filePath,
                'approval_status_manager' => 'APPROVED',
                'is_manager_signed'       => true,
                'tgl_manager'             => $currentDate,
            ]);
        } else {
            $k3->update([
                'ttd_tl'                  => $filePath,
                'approval_status_tl'      => 'APPROVED',
                'is_tl_signed'            => true,
                'tgl_tl'                  => $currentDate,
            ]);
        }

        return redirect()->route('k3.list')->with('success', 'Tanda tangan berhasil disimpan!');
    }

    public function downloadPdf($id)
    {
        $k3 = K3Inspection::findOrFail($id);
        $items = $k3->checklist_data ?? [];

        $engineerSign = $this->getImageBase64($k3->ttd_engineer);
        $tlSign       = $this->getImageBase64($k3->ttd_tl);
        $managerSign  = $this->getImageBase64($k3->ttd_manager);

        $fotoHelmet   = $this->getImageBase64($k3->foto_helmet);
        $fotoHarness  = $this->getImageBase64($k3->foto_harness);
        $fotoVestBiru = $this->getImageBase64($k3->foto_vest_biru);
        $fotoVestMerah = $this->getImageBase64($k3->foto_vest_merah);

        $pdf = Pdf::loadView('k3.pdf_template', compact(
            'k3', 'items', 'engineerSign', 'tlSign', 'managerSign',
            'fotoHelmet', 'fotoHarness', 'fotoVestBiru', 'fotoVestMerah'
        ))
        ->setPaper('a4', 'portrait')
        ->setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => [public_path(), storage_path()],
        ]);

        $fileName = 'Formulir_Persediaan_APD_' . str_replace('/', '_', $k3->doc_number) . '.pdf';

        return $pdf->stream($fileName);
    }

    private function saveBase64Image($base64Data, $prefix)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]);
            $data = base64_decode($data);

            if ($data === false) return null;

            $fileName = 'signatures/ttd_' . $prefix . '_' . time() . '.' . $type;
            Storage::disk('public')->put($fileName, $data);
            return $fileName;
        }
        return null;
    }

    private function getImageBase64($path)
    {
        if (empty($path)) return null;

        if (Storage::disk('public')->exists($path)) {
            $fullPath = Storage::disk('public')->path($path);
            if (file_exists($fullPath)) {
                $fileData = file_get_contents($fullPath);
                $mimeType = mime_content_type($fullPath);
                return 'data:' . $mimeType . ';base64,' . base64_encode($fileData);
            }
        }

        $publicPath = public_path('storage/' . $path);
        if (file_exists($publicPath)) {
            $fileData = file_get_contents($publicPath);
            $mimeType = mime_content_type($publicPath);
            return 'data:' . $mimeType . ';base64,' . base64_encode($fileData);
        }

        return null;
    }

    public function edit($id)
    {
        if (strtolower(auth()->user()->role ?? '') === 'admin') {
            return redirect()->route('k3.list')->with('error', 'Admin tidak memiliki hak akses untuk mengedit dokumen K3.');
        }

        $k3 = K3Inspection::findOrFail($id);
        $docNumber = $k3->doc_number;
        return view('k3.form', compact('k3', 'docNumber'));
    }

    public function update(Request $request, $id)
    {
        if (strtolower(auth()->user()->role ?? '') === 'admin') {
            return redirect()->route('k3.list')->with('error', 'Akses ditolak. Admin tidak diizinkan mengubah dokumen K3.');
        }

        $k3 = K3Inspection::findOrFail($id);

        $request->validate([
            'doc_number'    => 'required|string',
            'versi'         => 'required|string',
            'tgl_dokumen'   => 'required',
            'tahun'         => 'required',
            'nama_engineer' => 'required|string',
            'nama_tl'       => 'required|string',
            'nama_manager'  => 'required|string',
        ]);

        $data = [
            'doc_number'    => $request->doc_number,
            'versi'         => $request->versi,
            'tgl_dokumen'   => $request->tgl_dokumen,
            'tahun'         => $request->tahun,
            'nama_engineer' => $request->nama_engineer,
            'nama_tl'       => $request->nama_tl,
            'nama_manager'  => $request->nama_manager,
            'checklist_data' => json_encode($request->items ?? []),
        ];

        if (!empty($request->signature_engineer)) {
            $path = $this->saveBase64Image($request->signature_engineer, 'engineer');
            if ($path) {
                $data['ttd_engineer'] = $path;
            }
        }

        if ($request->hasFile('foto_helmet')) {
            $data['foto_helmet'] = $request->file('foto_helmet')->store('apd_photos', 'public');
        }
        if ($request->hasFile('foto_harness')) {
            $data['foto_harness'] = $request->file('foto_harness')->store('apd_photos', 'public');
        }
        if ($request->hasFile('foto_vest_biru')) {
            $data['foto_vest_biru'] = $request->file('foto_vest_biru')->store('apd_photos', 'public');
        }
        if ($request->hasFile('foto_vest_merah')) {
            $data['foto_vest_merah'] = $request->file('foto_vest_merah')->store('apd_photos', 'public');
        }

        $k3->update($data);

        return redirect()->route('k3.list')->with('success', 'Formulir K3 berhasil diperbarui!');
    }

   public function destroy($id)
{
    $inspection = K3Inspection::findOrFail($id);
    $inspection->delete();

    return redirect()->back()->with('success', 'Dokumen Inspeksi K3 berhasil dihapus!');
}
}