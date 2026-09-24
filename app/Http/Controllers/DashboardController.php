<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\K3Inspection;
use App\Models\Asset;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->calculateStats();
        
        // Data Tambahan untuk Fitur Baru di Dashboard
        $recentBorrowings = Borrowing::with(['user', 'asset'])->latest()->take(5)->get();
        $recentK3 = K3Inspection::latest()->take(5)->get();
        
        $overdueBorrowings = Borrowing::whereIn('status', ['dipinjam', 'DIPINJAM', 'borrowed', 'BORROWED'])
            ->where('return_date', '<', now())
            ->get();

        $criticalAssets = class_exists(Asset::class) ? Asset::where('stock', '<=', 2)->get() : collect();

        return view('dashboard', compact(
            'stats',
            'recentBorrowings',
            'recentK3',
            'overdueBorrowings',
            'criticalAssets'
        ));
    }

    /**
     * Method ini dipanggil oleh AJAX Javascript (route: dashboard.stats)
     */
    public function stats()
    {
        return response()->json($this->calculateStats());
    }

    /**
     * Alias jika route Anda menggunakan nama getStatsJson
     */
    public function getStatsJson()
    {
        return $this->stats();
    }

    /**
     * Menghitung statistik peminjaman dan otorisasi K3 berdasarkan peran pengguna
     */
    private function calculateStats()
    {
        $role = strtolower(auth()->user()->role ?? '');
        $k3PendingCount = 0;
        $k3SignedCount = 0;

        if ($role === 'tl') {
            $k3PendingCount = K3Inspection::where(function($q) {
                $q->whereNull('is_tl_signed')
                  ->orWhere('is_tl_signed', false)
                  ->orWhere('is_tl_signed', 0);
            })->count();

            $k3SignedCount = K3Inspection::where('is_tl_signed', true)
                ->orWhere('is_tl_signed', 1)
                ->count();

        } elseif ($role === 'manager') {
            $k3PendingCount = K3Inspection::where(function($q) {
                $q->whereNull('is_manager_signed')
                  ->orWhere('is_manager_signed', false)
                  ->orWhere('is_manager_signed', 0);
            })->count();

            $k3SignedCount = K3Inspection::where('is_manager_signed', true)
                ->orWhere('is_manager_signed', 1)
                ->count();
        }

        // Variasi penulisan status yang umum di database
        return [
            'total_pending'  => Borrowing::whereIn('status', ['pending', 'PENDING', 'menunggu', 'MENUNGGU'])->count(),
            'total_dipinjam' => Borrowing::whereIn('status', ['dipinjam', 'DIPINJAM', 'borrowed', 'BORROWED', 'approved', 'APPROVED'])->count(),
            'total_kembali'  => Borrowing::whereIn('status', ['dikembalikan', 'DIKEMBALIKAN', 'returned', 'RETURNED', 'kembali', 'KEMBALI'])->count(),
            'total_inspeksi' => K3Inspection::count(),
            'k3_pending'     => $k3PendingCount,
            'k3_signed'      => $k3SignedCount,
        ];
    }
}