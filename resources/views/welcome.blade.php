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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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

        /* STYLING DROPDOWN MENU ELEGAN */
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

        /* STYLING ITEM DROPDOWN */
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

        /* STAT CARD STYLING NATURAL & MODERN */
        .stat-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .stat-card-natural {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            padding: 20px;
            transition: all 0.25s ease-in-out;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        }

        .stat-card-link:hover .stat-card-natural {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -5px rgba(10, 58, 96, 0.1);
            border-color: var(--pln-cyan);
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
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
            font-size: 2rem;
            font-weight: 700;
            color: #0F172A;
            line-height: 1;
        }

        /* BUTTONS & BADGES */
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
            <button class="btn btn-outline-light border-0 me-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#appDrawer" aria-controls="appDrawer">
                <i class="bi bi-list fs-4"></i>
            </button>
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ==================== HAK AKSES KHUSUS ADMIN & PIC ==================== -->
    @if(in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'pic']))
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: var(--pln-navy);">
                <i class="bi bi-grid-fill text-primary"></i> Ringkasan Status & Menu Navigasi
            </h5>
            <div>
                @if(strtolower(Auth::user()->role ?? '') !== 'admin')
                    <a href="{{ route('k3.form') }}" class="btn btn-pln-orange btn-sm fw-bold rounded-pill px-3 shadow-sm py-2">
                        <i class="bi bi-file-earmark-plus me-1"></i> Form Input Checklist K3
                    </a>
                @endif
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <a href="{{ route('borrowings.pending') }}" class="stat-card-link">
                    <div class="stat-card-natural">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-title">Permohonan Peminjaman</div>
                                <div class="stat-subtitle">Belum Disetujui (Pending)</div>
                            </div>
                            <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-clock-history"></i>
                            </div>
                        </div>
                        <div class="stat-number" id="stat-pending">{{ $stats['total_pending'] ?? 0 }}</div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('borrowings.active') }}" class="stat-card-link">
                    <div class="stat-card-natural">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-title">Peminjaman Aset</div>
                                <div class="stat-subtitle">Barang Sedang Dipinjam</div>
                            </div>
                            <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                        <div class="stat-number" id="stat-dipinjam">{{ $stats['total_dipinjam'] ?? 0 }}</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <a href="{{ route('borrowings.returned') }}" class="stat-card-link">
                    <div class="stat-card-natural">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-title">Pengembalian Aset</div>
                                <div class="stat-subtitle">Sudah Dikembalikan</div>
                            </div>
                            <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                                <i class="bi bi-check2-circle"></i>
                            </div>
                        </div>
                        <div class="stat-number" id="stat-kembali">{{ $stats['total_kembali'] ?? 0 }}</div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('k3.list') }}" class="stat-card-link">
                    <div class="stat-card-natural">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-title">Dokumen Inspeksi K3</div>
                                <div class="stat-subtitle">Total Laporan Masuk</div>
                            </div>
                            <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-file-earmark-check"></i>
                            </div>
                        </div>
                        <div class="stat-number" id="stat-inspeksi">{{ $stats['total_inspeksi'] ?? 0 }}</div>
                    </div>
                </a>
            </div>
        </div>

    <!-- ==================== HAK AKSES KHUSUS TEAM LEADER (TL) & MANAGER ==================== -->
    @else

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0" style="color: var(--pln-navy);">
                <i class="bi bi-pen-fill me-2" style="color: var(--pln-orange);"></i>Portal Otorisasi & Tanda Tangan Dokumen Inspeksi K3
            </h5>
        </div>

        <div class="row g-4 mb-4">
            <!-- Kartu 1: Menunggu TTD -->
            <div class="col-md-6">
                <a href="{{ route('k3.list', ['status' => 'pending']) }}" class="stat-card-link">
                    <div class="stat-card-natural text-center py-4 h-100">
                        <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                            <i class="bi bi-file-earmark-clock-fill"></i>
                        </div>
                        <div class="stat-title fs-6">Dokumen Inspeksi K3 (Menunggu TTD)</div>
                        <div class="stat-subtitle mb-3">Klik Di Sini Untuk Membuka Daftar Dokumen & Memberikan Tanda Tangan</div>
                        <div class="stat-number text-warning" id="stat-k3-pending">{{ $stats['k3_pending'] ?? 0 }}</div>
                    </div>
                </a>
            </div>

            <!-- Kartu 2: Sudah Ditandatangani -->
            <div class="col-md-6">
                <a href="{{ route('k3.list', ['status' => 'signed']) }}" class="stat-card-link">
                    <div class="stat-card-natural text-center py-4 h-100">
                        <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success mx-auto mb-3">
                            <i class="bi bi-file-earmark-check-fill"></i>
                        </div>
                        <div class="stat-title fs-6">Dokumen Inspeksi K3 (Sudah TTD)</div>
                        <div class="stat-subtitle mb-3">Klik Di Sini Untuk Melihat Arsip Dokumen Yang Telah Selesai Ditandatangani</div>
                        <div class="stat-number text-success" id="stat-k3-signed">{{ $stats['k3_signed'] ?? 0 }}</div>
                    </div>
                </a>
            </div>
        </div>

    @endif
</div>

<!-- ==================== COMPONENT OFFCANVAS (DRAWER MENU) ==================== -->
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
            <a href="#" data-bs-toggle="modal" data-bs-target="#qrCodeModal" data-bs-dismiss="offcanvas" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
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

<!-- ==================== MODAL GENERATOR SMART QR CODE ==================== -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header text-white" style="background-color: var(--pln-navy); border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h5 class="modal-title fw-bold" id="qrCodeModalLabel">
                    <i class="bi bi-qr-code-scan me-2 text-info"></i>QR Code Peminjaman & Pengembalian Aset
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-7 mb-3 mb-md-0 pe-md-4">
                        <h6 class="fw-bold mb-2" style="color: var(--pln-navy);">QR Code Pintar (Peminjaman & Pengembalian)</h6>
                        <p class="text-muted small mb-2">QR Code ini terhubung langsung secara otomatis dengan status fisik barang:</p>
                        <ul class="small text-muted ps-3 mb-4">
                            <li class="mb-1"><strong>Status Barang Tersedia:</strong> Membuka <em>Form Peminjaman Barang</em>.</li>
                            <li><strong>Status Barang Dipinjam:</strong> Membuka <em>Form Pengembalian Barang</em>.</li>
                        </ul>
                        <div>
                            <label class="form-label fw-semibold small" style="color: var(--pln-navy);">Pilih / Masukkan Kode Barang (Item Code):</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-tag"></i></span>
                                <input type="text" id="itemCodeInput" class="form-control border-start-0" placeholder="Contoh: HELM-001 / HARNESS-02" value="PLN-01">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 text-center ps-md-4">
                        <div class="p-3 bg-white d-inline-block rounded-3 border shadow-sm mb-3">
                            <img id="qrCodeImage" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(url('/scan/PLN-01')) }}" alt="QR Code Peminjaman/Pengembalian" class="img-fluid" style="max-width: 150px;">
                        </div>
                        <div>
                            <span class="badge badge-navy fs-6 mb-2 px-3 py-2 rounded-pill" id="qrCodeLabel">Kode: PLN-01</span>
                            <br>
                            <a id="downloadBtn" href="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={{ urlencode(url('/scan/PLN-01')) }}" target="_blank" class="btn btn-sm btn-outline-dark fw-bold rounded-pill px-3" download>
                                <i class="bi bi-printer me-1"></i> Download / Cetak QR Code
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            confirmButtonColor: '#F26522',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Keluar...',
                    text: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                    willClose: () => {
                        document.getElementById('logout-form').submit();
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    icon: 'info',
                    title: 'Dibatalkan',
                    text: 'Jangan ragu-ragu ya!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
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

                    if (pendingEl) pendingEl.innerText = data.total_pending;
                    if (dipinjamEl) dipinjamEl.innerText = data.total_dipinjam;
                    if (kembaliEl) kembaliEl.innerText = data.total_kembali;
                    if (inspeksiEl) inspeksiEl.innerText = data.total_inspeksi;
                    if (k3PendingEl) k3PendingEl.innerText = data.k3_pending;
                    if (k3SignedEl) k3SignedEl.innerText = data.k3_signed;
                })
                .catch(error => console.error('Gagal memperbarui data otomatis:', error));
        }

        setInterval(fetchLatestStats, 5000);

        const inputField = document.getElementById('itemCodeInput');
        const qrImage = document.getElementById('qrCodeImage');
        const qrLabel = document.getElementById('qrCodeLabel');
        const downloadBtn = document.getElementById('downloadBtn');

        function updateSmartQr() {
            if (!inputField) return;
            let code = inputField.value.trim();
            if (!code) code = 'PLN-01';

            let hostUrl = window.location.origin;
            let scanTargetUrl = hostUrl + '/scan/' + encodeURIComponent(code);
            let qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + encodeURIComponent(scanTargetUrl);

            if (qrImage) qrImage.src = qrApiUrl;
            if (qrLabel) qrLabel.innerText = 'Kode: ' + code;
            if (downloadBtn) downloadBtn.href = qrApiUrl;
        }

        function generateAutoCode() {
            fetch("{{ route('borrowing.next-code') }}")
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (inputField && data.next_code) {
                        inputField.value = data.next_code;
                        updateSmartQr();
                    }
                })
                .catch(error => console.error('Gagal memuat kode otomatis:', error));
        }

        generateAutoCode();

        if (inputField) {
            inputField.addEventListener('input', function() {
                updateSmartQr();
            });

            inputField.addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    updateSmartQr();
                }
            });
        }
    });

    const qrModalElement = document.getElementById('qrCodeModal');
    if (qrModalElement) {
        qrModalElement.addEventListener('shown.bs.modal', function () {
            generateAutoCode();
        });
    }
</script>
</body>
</html>