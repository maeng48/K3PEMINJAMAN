<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utama - K3 Peminjaman System</title>

    <!-- BOOTSTRAP 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --pln-navy: #0A3A60;
            --pln-cyan: #00A3E0;
            --pln-orange: #F26522;
            --pln-bg: #F8FAFC;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--pln-bg);
            color: #1E293B;
        }

        /* NAVBAR STYLING */
        .navbar-custom {
            background-color: var(--pln-navy);
            border-bottom: 3px solid var(--pln-cyan);
        }

        /* STYLING & ANIMASI PROFIL NAVBAR */
        .dropdown > a {
            transition: all 0.25s ease-in-out;
            padding: 6px 12px;
            border-radius: 10px;
        }

        .dropdown > a:hover {
            background-color: rgba(255, 255, 255, 0.08);
        }

        .dropdown > a .rounded-circle {
            transition: transform 0.25s ease-in-out;
        }

        .dropdown > a:hover .rounded-circle {
            transform: scale(1.05);
        }

        /* STYLING DROPDOWN MENU */
        .dropdown-menu {
            animation: dropdownAnimation 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: top right;
            border: none;
            box-shadow: 0 10px 25px -5px rgba(10, 58, 96, 0.15), 0 8px 10px -6px rgba(10, 58, 96, 0.1) !important;
            border-radius: 14px;
            padding: 8px;
            margin-top: 10px !important;
            background-color: #ffffff;
        }

        @keyframes dropdownAnimation {
            0% {
                opacity: 0;
                transform: translateY(-8px) scale(0.96);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #F1F5F9;
            color: var(--pln-navy);
            transform: translateX(3px);
        }

        .dropdown-item.text-danger:hover {
            background-color: #FEF2F2;
            color: #DC2626 !important;
        }

        /* STAT CARD HOVER EFFECT & CURSOR INTERACTION */
        .card-hover {
            transition: all 0.25s ease-in-out;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .card-hover:hover {
            transform: translateY(-6px) !important;
            box-shadow: 0 14px 28px -5px rgba(10, 58, 96, 0.15) !important;
        }

        /* EFEK GRADIENT PADA KARTU STATUS */
        .card-gradient-pending {
            border-left: 5px solid #f59e0b !important;
            background: linear-gradient(135deg, #ffffff 60%, #fffbeb 100%) !important;
        }

        .card-gradient-active {
            border-left: 5px solid #0284c7 !important;
            background: linear-gradient(135deg, #ffffff 60%, #f0f9ff 100%) !important;
        }

        .card-gradient-returned {
            border-left: 5px solid #10b981 !important;
            background: linear-gradient(135deg, #ffffff 60%, #ecfdf5 100%) !important;
        }

        .card-gradient-k3 {
            border-left: 5px solid #6366f1 !important;
            background: linear-gradient(135deg, #ffffff 60%, #eef2ff 100%) !important;
        }

        .stat-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .stat-card-natural {
            border-radius: 20px;
            padding: 24px 20px;
            transition: all 0.25s ease-in-out;
            box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.03);
            cursor: pointer;
        }

        .stat-card-link:hover .stat-card-natural {
            transform: translateY(-6px);
            box-shadow: 0 14px 28px -5px rgba(10, 58, 96, 0.15) !important;
        }

        .icon-box-pending {
            width: 72px;
            height: 72px;
            border-radius: 22px;
            background-color: #FFF8E7;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-box-signed {
            width: 72px;
            height: 72px;
            border-radius: 22px;
            background-color: #E6F4EA;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--pln-navy);
            margin-bottom: 2px;
        }

        .stat-subtitle {
            font-size: 0.75rem;
            color: #64748B;
            margin-bottom: 12px;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1;
        }

        .btn-pln-orange {
            background-color: var(--pln-orange);
            color: #ffffff;
            border: none;
            transition: all 0.2s;
        }

        .btn-pln-orange:hover {
            background-color: #d95316;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(242, 101, 34, 0.25);
        }

        .badge-navy {
            background-color: var(--pln-navy);
            color: #ffffff;
        }
    </style>
</head>
<body>

<!-- NAVBAR UTAMA -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-4 py-3">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center gap-2">
            @if(in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'pic']))
                <button class="btn btn-outline-light border-0 me-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#appDrawer" aria-controls="appDrawer">
                    <i class="bi bi-list fs-4"></i>
                </button>
            @endif
            
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
                <span>K3 & PEMINJAMAN</span>
            </a>
        </div>

        <div class="d-flex align-items-center gap-3">
            @if(strtolower(Auth::user()->role ?? '') === 'admin')
                <a href="{{ route('users.index') }}" class="btn btn-light btn-sm fw-bold shadow-sm d-flex align-items-center gap-1 text-dark px-3 py-2 rounded-pill">
                    <i class="bi bi-people-fill text-primary"></i> Manajemen Pengguna
                </a>
            @endif

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold shadow-sm" style="width: 38px; height: 38px; font-size: 15px;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="d-none d-md-block text-start me-1">
                        <span class="d-block small fw-bold lh-1 text-white">{{ Auth::user()->name ?? 'User' }}</span>
                        <span class="badge bg-info text-dark mt-1 px-2 py-0.5" style="font-size: 9px; font-weight: 600;">{{ strtoupper(Auth::user()->role ?? 'TL') }}</span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser" style="min-width: 220px;">
                    <li class="px-3 py-2 border-bottom mb-1 bg-light rounded-top-3">
                        <span class="d-block text-muted" style="font-size: 11px;">Masuk sebagai:</span>
                        <strong class="d-block text-dark text-truncate" style="font-size: 13px;">{{ Auth::user()->name ?? 'User' }}</strong>
                        <span class="badge bg-secondary mt-1" style="font-size: 9px;">{{ strtoupper(Auth::user()->role ?? 'TL') }}</span>
                    </li>
                    
                    <li>
                        <a class="dropdown-item py-2 text-dark d-flex align-items-center gap-2" href="{{ route('password.change') }}">
                            <i class="bi bi-key text-primary fs-5"></i> <span>Ubah Password</span>
                        </a>
                    </li>
                    
                    <li><hr class="dropdown-divider my-1"></li>
                    
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="button" onclick="confirmLogout(event)" class="dropdown-item py-2 text-danger d-flex align-items-center gap-2 fw-semibold">
                                <i class="bi bi-box-arrow-right fs-5"></i> <span>Keluar (Logout)</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid px-4 py-2">

    <!-- ALERT NOTIFIKASI DENGAN ID KUSTOM UNTUK AUTO-DISMISS -->
    @if(session('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div id="errorAlert" class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ==================== HAK AKSES KHUSUS ADMIN & PIC ==================== -->
    @if(in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'pic']))
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: var(--pln-navy);">
                <i class="bi bi-grid-fill text-primary"></i> Ringkasan Status
            </h5>
            <div>
                @if(strtolower(Auth::user()->role ?? '') !== 'admin')
                    <a href="{{ route('k3.form') }}" class="btn btn-pln-orange btn-sm fw-bold rounded-pill px-3 shadow-sm py-2">
                        <i class="bi bi-file-earmark-plus me-1"></i> Form Input Checklist K3
                    </a>
                @endif
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- 1. Permohonan Peminjaman -->
            <div class="col-12 col-md-6">
                <a href="{{ route('borrowings.pending') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-hover card-gradient-pending">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Permohonan Peminjaman</h6>
                                <p class="text-muted small mb-4">Belum Disetujui (Pending)</p>
                                <h2 class="fw-extrabold mb-0 text-dark" id="stat-pending" style="font-size: 2.8rem; font-weight: 800;">
                                    {{ $stats['total_pending'] ?? 0 }}
                                </h2>
                            </div>
                            <div class="rounded-4 p-3 d-flex align-items-center justify-content-center shadow-sm" 
                                 style="background-color: #fff8e7; color: #f59e0b; width: 68px; height: 68px;">
                                <i class="bi bi-clock-history fs-1"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div> 

            <!-- 2. Peminjaman Aset -->
            <div class="col-12 col-md-6">
                <a href="{{ route('borrowings.active') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-hover card-gradient-active">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Peminjaman Aset</h6>
                                <p class="text-muted small mb-4">Barang Sedang Dipinjam</p>
                                <h2 class="fw-extrabold mb-0 text-dark" id="stat-dipinjam" style="font-size: 2.8rem; font-weight: 800;">
                                    {{ $stats['total_dipinjam'] ?? 0 }}
                                </h2>
                            </div>
                            <div class="rounded-4 p-3 d-flex align-items-center justify-content-center shadow-sm" 
                                 style="background-color: #e0f2fe; color: #0284c7; width: 68px; height: 68px;">
                                <i class="bi bi-box-seam fs-1"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 3. Pengembalian Aset -->
            <div class="col-12 col-md-6">
                <a href="{{ route('borrowings.returned') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-hover card-gradient-returned">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Pengembalian Aset</h6>
                                <p class="text-muted small mb-4">Sudah Dikembalikan</p>
                                <h2 class="fw-extrabold mb-0 text-dark" id="stat-kembali" style="font-size: 2.8rem; font-weight: 800;">
                                    {{ $stats['total_kembali'] ?? 0 }}
                                </h2>
                            </div>
                            <div class="rounded-4 p-3 d-flex align-items-center justify-content-center shadow-sm" 
                                 style="background-color: #e6f4ea; color: #10b981; width: 68px; height: 68px;">
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 4. Dokumen Inspeksi K3 -->
            <div class="col-12 col-md-6">
                <a href="{{ route('k3.list') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-hover card-gradient-k3">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Dokumen Inspeksi K3</h6>
                                <p class="text-muted small mb-4">Total Laporan Masuk</p>
                                <h2 class="fw-extrabold mb-0 text-dark" id="stat-inspeksi" style="font-size: 2.8rem; font-weight: 800;">
                                    {{ $stats['total_inspeksi'] ?? 0 }}
                                </h2>
                            </div>
                            <div class="rounded-4 p-3 d-flex align-items-center justify-content-center shadow-sm" 
                                 style="background-color: #eef2ff; color: #6366f1; width: 68px; height: 68px;">
                                <i class="bi bi-file-earmark-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    <!-- ==================== HAK AKSES KHUSUS TEAM LEADER (TL) & MANAGER ==================== --
    @else

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0" style="color: var(--pln-navy);">
                <i class="bi bi-pen-fill me-2" style="color: var(--pln-orange);"></i>Tanda Tangan Dokumen Inspeksi K3
            </h5>
        </div>

        <div class="row g-4 mb-4">
            <!-- KARTU 1: DOKUMEN MENUNGGU TTD -->
            <div class="col-md-6">
                <a href="{{ route('k3.list', ['status' => 'pending']) }}" class="stat-card-link">
                    <div class="stat-card-natural card-hover card-gradient-pending text-center py-4 h-100">
                        <div class="icon-box-pending mx-auto mb-3 shadow-sm">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21C16.9706 21 21 16.9706 21 12" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/>
                                <path d="M16 3.5C18.5 4.8 20.5 7.2 21 10" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-dasharray="2 3"/>
                                <path d="M12 7V12H16" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="stat-title fs-6">Dokumen Inspeksi K3 (Menunggu TTD)</div>
                        <div class="stat-subtitle mb-3">Daftar Dokumen & Tanda Tangan</div>
                        <div class="stat-number text-warning" id="stat-k3-pending">{{ $stats['k3_pending'] ?? 0 }}</div>
                    </div>
                </a>
            </div>

            <!-- KARTU 2: DOKUMEN SUDAH DITANDATANGANI -->
            <div class="col-md-6">
                <a href="{{ route('k3.list', ['status' => 'signed']) }}" class="stat-card-link">
                    <div class="stat-card-natural card-hover card-gradient-returned text-center py-4 h-100">
                        <div class="icon-box-signed mx-auto mb-3 shadow-sm">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="#0D9488" stroke-width="2" stroke-linecap="round"/>
                                <path d="M9 12L11.5 14.5L16.5 9.5" stroke="#0D9488" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="stat-title fs-6">Dokumen Inspeksi K3 (Sudah TTD)</div>
                        <div class="stat-subtitle mb-3">Arsip Dokumen Yang Sudah Ditandatangani</div>
                        <div class="stat-number text-success" id="stat-k3-signed">{{ $stats['k3_signed'] ?? 0 }}</div>
                    </div>
                </a>
            </div>
        </div>

    @endif
</div>

<!-- ==================== COMPONENT OFFCANVAS (DRAWER MENU - HANYA ADMIN & PIC) ==================== -->
@if(in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'pic']))
<div class="offcanvas offcanvas-start" tabindex="-1" id="appDrawer" aria-labelledby="appDrawerLabel" style="border-top-right-radius: 16px; border-bottom-right-radius: 16px;">
    <div class="offcanvas-header text-white" style="background-color: var(--pln-navy);">
        <h5 class="offcanvas-title fw-bold d-flex align-items-center gap-2" id="appDrawerLabel">
            <i class="bi bi-grid-fill text-info"></i> Menu Navigasi
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4">
        <p class="text-muted small mb-3">Akses cepat pemindaian dan pembuatan QR Code aset.</p>
        <div class="list-group shadow-sm mb-4">
            <a href="#" data-bs-toggle="modal" data-bs-target="#smartQrModal" data-bs-dismiss="offcanvas" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                <div class="bg-info bg-opacity-10 p-2 rounded-circle text-info">
                    <i class="bi bi-qr-code-scan fs-5"></i>
                </div>
                <div>
                    <strong class="d-block text-dark">Smart QR Code</strong>
                    <small class="text-muted">Generate QR Code Aset</small>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- ======================================================= -->
<!-- MODAL SMART QR CODE GENERATOR (VERSI ANTI-BENTROK)      -->
<!-- ======================================================= -->
<div class="modal fade smart-qr-modal-container" id="smartQrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header px-4 py-3 text-white" style="background-color: var(--pln-navy); border-bottom: none;">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2 mb-0" style="font-size: 1.05rem;">
                    <i class="bi bi-qr-code"></i> QR Code Peminjaman & Pengembalian Aset
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.8rem;"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                <div class="row align-items-center">
                    <div class="col-md-7 mb-4 mb-md-0">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;">QR Code Pintar (Peminjaman & Pengembalian)</h6>
                        <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                            QR Code ini terhubung langsung secara otomatis dengan status fisik barang:
                        </p>
                        <ul class="text-muted ps-3 mb-4" style="font-size: 0.85rem; line-height: 1.6;">
                            <li class="mb-1"><strong>Status Barang Tersedia:</strong> Membuka Form Peminjaman Barang.</li>
                            <li><strong>Status Barang Dipinjam:</strong> Membuka Form Pengembalian Barang.</li>
                        </ul>
                        <div>
                            <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.85rem;">Pilih / Masukkan Kode Barang (Item Code):</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted px-3" style="border-color: #e2e8f0;">
                                    <i class="bi bi-tag"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0 shadow-none qr-input-code-target" value="PLN-01" placeholder="Contoh: PLN-01" style="font-size: 0.9rem; border-color: #e2e8f0;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="mb-3">
                            <img class="qr-image-target" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/scan/PLN-01')) }}" alt="QR Code" style="width: 150px; height: 150px; object-fit: contain;">
                        </div>
                        <div class="mb-3">
                            <span class="badge rounded-pill px-4 py-2 shadow-sm qr-badge-target" style="background-color: var(--pln-navy); font-size: 0.85rem; font-weight: 600;">
                                Kode: PLN-01
                            </span>
                        </div>
                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(url('/scan/PLN-01')) }}" target="_blank" class="btn btn-light rounded-pill w-100 fw-semibold py-2 shadow-sm qr-download-target" style="font-size: 0.85rem; color: #475569; border: 1px solid #e2e8f0; transition: all 0.2s;">
                            <i class="bi bi-printer me-1"></i> Download / Cetak QR Code
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- SCRIPT BOOTSTRAP & SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Selamat Datang',
        text: "{{ session('success') }}",
        confirmButtonText: 'OK',
        confirmButtonColor: '#0A3A60',
        allowOutsideClick: false
    });
</script>
@endif

<script>
    function confirmLogout(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Keluar dari Sistem?',
            text: 'Anda akan keluar dari akun K3 Peminjaman.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#F26522', // Warna Oranye PLN
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            reverseButtons: true, // Menaruh tombol aksi utama di sebelah kanan
            allowOutsideClick: false,
            customClass: { popup: 'rounded-4 shadow-lg' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Keluar...',
                    text: 'Mohon tunggu sebentar.',
                    icon: 'success',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1200,
                    timerProgressBar: true,
                    willClose: () => {
                        document.getElementById('logout-form').submit();
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Animasi pop-up saat tombol Batal diklik
                Swal.fire({
                    title: 'Dibatalkan',
                    text: 'Anda masih berada di dalam sistem.',
                    icon: 'info',
                    timer: 1100,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-4 shadow-lg' }
                });
            }
        });
    }

    function generateAutoCode() {
        const modal = document.getElementById('smartQrModal');
        if (!modal) return;
        const inputField = modal.querySelector('.qr-input-code-target');
        
        fetch("{{ route('borrowing.next-code') }}")
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (inputField && data.next_code) {
                    inputField.value = data.next_code;
                    // Trigger otomatis untuk menggambar ulang QR
                    inputField.dispatchEvent(new Event('input', { bubbles: true }));
                }
            })
            .catch(error => console.error('Gagal memuat kode otomatis:', error));
    }

    document.addEventListener("DOMContentLoaded", function() {
        // AUTOMATIC DISMISS UNTUK ALERT NOTIFIKASI
        const successAlert = document.getElementById('successAlert');
        if (successAlert) {
            setTimeout(function () {
                const bsAlert = new bootstrap.Alert(successAlert);
                bsAlert.close();
            }, 2000);
        }

        const errorAlert = document.getElementById('errorAlert');
        if (errorAlert) {
            setTimeout(function () {
                const bsAlert = new bootstrap.Alert(errorAlert);
                bsAlert.close();
            }, 2000);
        }

        function fetchLatestStats() {
            fetch("{{ route('dashboard.stats') }}")
                .then(response => response.json())
                .then(data => {
                    const pendingEl = document.getElementById('stat-pending');
                    const dipinjamEl = document.getElementById('stat-dipinjam');
                    const kembaliEl = document.getElementById('stat-kembali');
                    const inspeksiEl = document.getElementById('stat-inspeksi');
                    const k3PendingEl = document.getElementById('stat-k3-pending');
                    const k3SignedEl = document.getElementById('stat-k3-signed');

                    if (pendingEl) pendingEl.innerText = data.total_pending ?? 0;
                    if (dipinjamEl) dipinjamEl.innerText = data.total_dipinjam ?? 0;
                    if (kembaliEl) kembaliEl.innerText = data.total_kembali ?? 0;
                    if (inspeksiEl) inspeksiEl.innerText = data.total_inspeksi ?? 0;
                    if (k3PendingEl) k3PendingEl.innerText = data.k3_pending ?? 0;
                    if (k3SignedEl) k3SignedEl.innerText = data.k3_signed ?? 0;
                })
                .catch(error => console.error('Gagal memperbarui data otomatis:', error));
        }

        fetchLatestStats();
        setInterval(fetchLatestStats, 5000); // Update stat dashboard setiap 5 detik

        // ================================================================
        // 1. SCRIPT REAL-TIME AUTO REFRESH QR CODE (ANTI-BENTROK)
        // ================================================================
        document.body.addEventListener('input', function(e) {
            if (e.target && e.target.classList.contains('qr-input-code-target')) {
                let code = e.target.value.trim();
                if (code === '') {
                    code = 'PLN-01'; // Default
                }
                
                let scanUrl = "{{ url('/scan') }}/" + encodeURIComponent(code);
                let modal = e.target.closest('.smart-qr-modal-container');
                
                if (modal) {
                    let qrImage = modal.querySelector('.qr-image-target');
                    let downloadBtn = modal.querySelector('.qr-download-target');
                    let codeBadge = modal.querySelector('.qr-badge-target');
                    
                    if (qrImage) qrImage.src = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + encodeURIComponent(scanUrl);
                    if (downloadBtn) downloadBtn.href = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" + encodeURIComponent(scanUrl);
                    if (codeBadge) codeBadge.textContent = "Kode: " + code;
                }
            }
        });

        // Trigger generateAutoCode saat Modal terbuka (khusus di Dashboard)
        const qrModalElement = document.getElementById('smartQrModal');
        if (qrModalElement) {
            qrModalElement.addEventListener('shown.bs.modal', function () {
                generateAutoCode();
            });
        }
    });
</script>
</body>
</html> 