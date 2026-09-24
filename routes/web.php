<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\K3Controller;
use App\Http\Controllers\UserController;

// RUTE UTAMA: Langsung mengarahkan ke halaman login
Route::redirect('/', '/login');

// ROUTE PUBLIC & AUTH LOGIN
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'username' => ['required'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // MENGHINDARI ERROR 404: Mengarahkan langsung ke nama route 'dashboard'
        return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . (Auth::user()->name ?? Auth::user()->username) . '!');
    }

    return back()->withErrors(['username' => 'Username atau password salah.']);
})->name('login.post');

Route::get('/get-next-code', [BorrowingController::class, 'getNextCode'])->name('borrowing.next-code');

// Rute Peminjaman via QR
Route::get('/scan/{id}', [BorrowingController::class, 'scanQr'])->name('borrowing.scan');
Route::post('/scan/{id}', [BorrowingController::class, 'storeBorrowing'])->name('borrowing.store');

// RUTE PENGEMBALIAN & STATUS CHECK (Di luar middleware auth agar bisa diakses peminjam via HP)
Route::get('/return/{id}', [BorrowingController::class, 'showReturnForm'])->name('borrowing.return.form');
Route::post('/return/{id}', [BorrowingController::class, 'processReturn'])->name('borrowing.return.process');

/* ROUTE STATUS CHECK REAL-TIME (Dapat dipanggil JavaScript HP Peminjam)*/

Route::get('/borrowings/status-check/{id}', [BorrowingController::class, 'checkStatus'])->name('borrowing.status.check');
Route::get('/borrowings/status-single-check/{id}', [BorrowingController::class, 'checkSingleStatus'])->name('borrowing.status.single.check');

    
// ROUTE DENGAN AUTENTIKASI (Wajib Login)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [BorrowingController::class, 'dashboard'])->name('dashboard');

    // Rute AJAX untuk Auto-Refresh Statistik Dashboard
    Route::get('/api/dashboard-stats', [DashboardController::class, 'getStatsJson'])->name('dashboard.stats');

    // Route Peminjaman Aset (Diakses Admin / PIC)
    Route::get('/borrowings/pending', [BorrowingController::class, 'indexPending'])->name('borrowings.pending');
    Route::get('/borrowings/active', [BorrowingController::class, 'indexActive'])->name('borrowings.active');
    Route::get('/borrowings/returned', [BorrowingController::class, 'indexReturned'])->name('borrowings.returned');
    
    // Rute Approve & Reject menggunakan POST
    Route::post('/borrowings/approve/{id}', [BorrowingController::class, 'approve'])->name('borrowing.approve');
    Route::post('/borrowings/reject/{id}', [BorrowingController::class, 'reject'])->name('borrowing.reject');
    
    // Rute AJAX tambahan untuk Approve/Reject
    Route::post('/borrowings/{id}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::post('/borrowings/{id}/reject', [BorrowingController::class, 'reject'])->name('borrowings.reject');
    
    Route::get('/assets', [BorrowingController::class, 'index'])->name('assets.index');

    // Route khusus Dokumen K3
    Route::get('/k3/list', [K3Controller::class, 'indexList'])->name('k3.list');
    Route::get('/k3/form/{id?}', [K3Controller::class, 'form'])->name('k3.form');
    Route::post('/k3/save', [K3Controller::class, 'save'])->name('k3.save');

    // Route Edit dan Hapus K3
    Route::get('/k3/edit/{id}', [K3Controller::class, 'edit'])->name('k3.edit');
    Route::put('/k3/update/{id}', [K3Controller::class, 'update'])->name('k3.update');
    Route::delete('/k3/{id}', [K3Controller::class, 'destroy'])->name('k3.destroy');

    // Route khusus Tanda Tangan Team Leader (TL) & Manager
    Route::get('/k3/sign-preview/{id}', [K3Controller::class, 'showSignPage'])->name('k3.sign.preview');
    Route::get('/k3/manager-sign-preview/{id}', [K3Controller::class, 'showManagerSignPage'])->name('k3.manager.sign.preview');

    // Process Tanda Tangan Canvas (POST Base64 Image)
    Route::post('/k3/sign-process/{id}', [K3Controller::class, 'processSignature'])->name('k3.sign.process');
    
    Route::get('/k3/pdf/{id}', [K3Controller::class, 'downloadPdf'])->name('k3.pdf');

    // Rute Manajemen Pengguna (Admin)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Ubah Password & Logout
    Route::get('/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'updatePassword'])->name('password.update');
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar (logout).');
    })->name('logout');
    
    Route::delete('/borrowings/{id}', [BorrowingController::class, 'destroy'])->name('borrowings.destroy');
});