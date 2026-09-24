<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Peminjaman (Pending) - K3 System</title>

    <!-- BOOTSTRAP 5 & ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --pln-navy: #0A3A60;
            --pln-cyan: #00A3E0;
            --pln-bg: #F8FAFC;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--pln-bg);
            color: #1E293B;
        }

        .navbar-custom {
            background-color: var(--pln-navy);
            border-bottom: 3px solid var(--pln-cyan);
            padding: 14px 24px;
        }

        .btn-quick-nav {
            background-color: #ffffff;
            border: 1px solid #E2E8F0;
            color: #475569;
            font-weight: 600;
            font-size: 0.8rem;
            border-radius: 12px;
            padding: 10px 14px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-quick-nav:hover {
            background-color: #F1F5F9;
            color: var(--pln-navy);
            border-color: var(--pln-cyan);
            transform: translateY(-1px);
        }

        .btn-quick-nav.active {
            background-color: var(--pln-navy);
            color: #ffffff;
            border-color: var(--pln-navy);
            box-shadow: 0 4px 12px rgba(10, 58, 96, 0.2) !important;
        }

        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            overflow: hidden;
        }

        .card-custom-header {
            background-color: var(--pln-navy);
            color: #ffffff;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .badge-total-cyan {
            background-color: var(--pln-cyan);
            color: #ffffff;
            font-weight: 700;
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 0.85rem;
        }

        .control-row {
            padding: 16px 24px;
            background-color: #ffffff;
            border-bottom: 1px solid #F1F5F9;
        }

        .table-custom {
            margin-bottom: 0;
            font-size: 0.825rem;
            vertical-align: middle;
        }

        .table-custom thead th {
            background-color: #F8FAFC;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.725rem;
            letter-spacing: 0.5px;
            padding: 14px 12px;
            border-bottom: 2px solid #E2E8F0;
        }

        .table-custom tbody td {
            padding: 14px 12px;
            color: #334155;
            border-bottom: 1px solid #F1F5F9;
        }

        .badge-divisi {
            background-color: #E0F2FE;
            color: #0284C7;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 50px;
            display: inline-block;
            margin-top: 3px;
        }

        .badge-status-pending {
            background-color: #F59E0B;
            color: #ffffff;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 0.725rem;
        }

        .badge-code {
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            color: #0F172A;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 8px;
            font-size: 0.725rem;
            display: inline-block;
        }

        .btn-photo-pill {
            background-color: #FFFFFF;
            border: 1px solid #CBD5E1;
            color: #0284C7;
            font-size: 0.65rem; 
            padding: 4px 10px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap; 
            transition: all 0.2s;
        }

        .btn-photo-pill:hover {
            background-color: #F0F9FF;
            border-color: #0284C7;
            color: #0369A1;
        }

        .btn-approve {
            background-color: #10B981;
            color: #ffffff;
            font-weight: 600;
            border-radius: 50px;
            padding: 4px 14px;
            font-size: 0.75rem;
            border: none;
            transition: all 0.2s;
        }

        .btn-approve:hover {
            background-color: #059669;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }

        .btn-reject {
            background-color: #EF4444;
            color: #ffffff;
            font-weight: 600;
            border-radius: 50px;
            padding: 4px 14px;
            font-size: 0.75rem;
            border: none;
            transition: all 0.2s;
        }

        .btn-reject:hover {
            background-color: #DC2626;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
        }

        .table-footer {
            padding: 16px 24px;
            background-color: #ffffff;
            border-top: 1px solid #F1F5F9;
        }
    </style>
</head>
<body>

<!-- NAVBAR UTAMA -->
<nav class="navbar navbar-dark navbar-custom shadow-sm mb-4">
    <div class="container-fluid px-2">
        <div class="d-flex align-items-center gap-3">
            @if(in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'pic']))
                <button class="btn btn-link text-light p-0 border-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
                    <i class="bi bi-list" style="font-size: 1.8rem;"></i>
                </button>
            @endif
            <span class="navbar-brand fw-bold fs-5 mb-0">K3 PEMINJAMAN SYSTEM</span>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm fw-bold rounded-pill px-3">
            <i class="bi bi-house me-1"></i> Kembali ke Dashboard
        </a>
    </div>
</nav>

<!-- OFFCANVAS SIDEBAR MENU (HANYA ADMIN & PIC) -->
@if(in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'pic']))
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel" style="width: 320px;">
    <div class="offcanvas-header" style="background-color: var(--pln-navy); color: white;">
        <h5 class="offcanvas-title fw-bold d-flex align-items-center gap-2" id="offcanvasMenuLabel">
            <i class="bi bi-grid-1x2-fill"></i> Menu Navigasi
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" style="background-color: #F8FAFC;">
        <p class="text-muted small mb-4">Akses cepat pemindaian dan pembuatan QR Code aset.</p>
        
        <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#smartQrModal" data-bs-dismiss="offcanvas">
            <div class="card border-0 shadow-sm rounded-3" style="transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="p-2 bg-light rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-qr-code-scan fs-4" style="color: #00A3E0;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Smart QR Code</h6>
                        <small class="text-muted">Generate QR Code Aset</small>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endif

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

    <!-- SHORTCUT QUICK NAVIGATION BAR -->
    @if(in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'pic']))
        <div class="row g-2 mb-4">
            <div class="col-6 col-md-3">
                <a href="{{ route('borrowings.pending') }}" class="btn w-100 btn-quick-nav {{ request()->routeIs('borrowings.pending') ? 'active' : '' }} shadow-sm">
                    <i class="bi bi-clock-history me-2 text-warning"></i> Permohonan (Pending)
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('borrowings.active') }}" class="btn w-100 btn-quick-nav {{ request()->routeIs('borrowings.active') ? 'active' : '' }} shadow-sm">
                    <i class="bi bi-box-seam me-2 text-info"></i> Sedang Dipinjam
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('borrowings.returned') }}" class="btn w-100 btn-quick-nav {{ request()->routeIs('borrowings.returned') ? 'active' : '' }} shadow-sm">
                    <i class="bi bi-check2-circle me-2 text-success"></i> Sudah Dikembalikan
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('k3.list') }}" class="btn w-100 btn-quick-nav {{ request()->routeIs('k3.list') ? 'active' : '' }} shadow-sm">
                    <i class="bi bi-file-earmark-check me-2 text-primary"></i> Dokumen Inspeksi K3
                </a>
            </div>
        </div>
    @endif

    <!-- MAIN CARD -->
    <div class="card-custom">
        <div class="card-custom-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-clock-history fs-5"></i>
                <h6 class="fw-bold mb-0">Permohonan Peminjaman (Perlu Approval)</h6>
            </div>
            <span class="badge-total-cyan shadow-sm">
                Total: {{ count($borrowings ?? []) }} Data
            </span>
        </div>

        <div class="control-row d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2 text-muted small">
                <span>Tampilkan</span>
                <select id="entriesSelect" class="form-select form-select-sm" style="width: 70px;">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span>data per halaman</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="searchInput" class="text-muted small fw-semibold mb-0"><i class="bi bi-search text-primary"></i> Cari Data:</label>
                <input type="text" id="searchInput" class="form-control form-control-sm" style="width: 200px;" placeholder="Ketik kata kunci...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom text-center align-middle" id="borrowingsTable">
               <thead>
                    <tr>
                        <th style="width: 4%;">NO</th>
                        <th style="width: 14%;">PEMINJAM & DIVISI</th>
                        <th style="width: 12%;">KEPERLUAN & CATATAN</th>
                        <th style="width: 12%;">BARANG / TOOLS</th>
                        <th style="width: 9%;">QR CODE</th>
                        <th style="width: 12%;">TGL & FOTO PINJAM</th>
                        <th style="width: 9%;">STATUS BARANG</th>
                        <th style="width: 11%;">TGL & FOTO KEMBALI</th>
                        <th style="width: 9%;">STATUS APPROVAL</th>
                        <th style="width: 8%;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings ?? [] as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong class="d-block text-dark">{{ $item->peminjam_nama ?? '-' }}</strong>
                                <span class="badge-divisi">Divisi: {{ $item->divisi ?? 'Ritel' }}</span>
                            </td>
                            <td>
                                <strong class="d-block text-dark" style="font-size: 11px;">{{ $item->keperluan ?? '-' }}</strong>
                                <span class="text-muted d-block mt-1" style="font-size: 10px;">
                                    <i class="bi bi-chat-text me-1"></i> {{ $item->catatan ?? 'Tidak ada catatan' }}
                                </span>
                            </td>
                            
                            <td>
                                <strong class="d-block text-dark">{{ $item->merk_barang ?? 'Modem' }}</strong>
                                <small class="text-muted" style="font-size: 11px;">Kategori: {{ $item->kategori_barang ?? 'Tools' }}</small>
                            </td>
                            <td>
                                <span class="badge-code">{{ $item->qr_code_id ?? 'PLN-06' }}</span>
                                <a href="{{ url('/scan/' . ($item->qr_code_id ?? 'PLN-06')) }}" target="_blank" class="text-decoration-none small d-block mt-1" style="font-size: 11px; color: #00A3E0;">
                                    <i class="bi bi-link-45deg"></i> Link Scan
                                </a>
                            </td>
                            <td>
                                <div class="text-muted small mb-1" style="font-size: 11px;">{{ !empty($item->tgl_pinjam) ? \Carbon\Carbon::parse($item->tgl_pinjam)->format('d-m-Y') : date('d-m-Y') }}</div>
                                @if(!empty($item->foto_pinjam))
                                    <a href="{{ asset('storage/' . str_replace('storage/', '', $item->foto_pinjam)) }}" target="_blank" class="btn-photo-pill">
                                        <i class="bi bi-camera"></i> Foto Peminjaman
                                    </a>
                                @else
                                    <span class="btn-photo-pill opacity-50" style="cursor: not-allowed;">
                                        <i class="bi bi-camera"></i> Foto Peminjaman
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-status-pending">{{ strtoupper($item->status ?? 'PENDING') }}</span>
                            </td>
                            <td>
                                <div class="text-muted small mb-1" style="font-size: 11px;">{{ !empty($item->tgl_kembali) ? \Carbon\Carbon::parse($item->tgl_kembali)->format('Y-m-d') : 'Belum Kembali' }}</div>
                                @if(!empty($item->foto_kembali))
                                    <a href="{{ asset('storage/' . str_replace('storage/', '', $item->foto_kembali)) }}" target="_blank" class="btn-photo-pill">
                                        <i class="bi bi-camera"></i> Bukti Kembali
                                    </a>
                                @endif
                            </td>
                            <td>
                                <span class="badge-status-pending">{{ strtoupper($item->approval_status ?? 'MENUNGGU APPROVAL') }}</span>
                            </td>
                            
                            <td>
                                <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                                    <form id="approve-form-{{ $item->id }}" action="{{ route('borrowings.approve', $item->id) }}" method="POST" class="w-100 m-0">
                                        @csrf
                                        <button type="button" class="btn btn-approve w-100 d-flex justify-content-center align-items-center gap-1" onclick="confirmApprove({{ $item->id }}, '{{ $item->peminjam_nama ?? 'Peminjam' }}')">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                    </form>

                                    <form id="reject-form-{{ $item->id }}" action="{{ route('borrowings.reject', $item->id) }}" method="POST" class="w-100 m-0">
                                        @csrf
                                        <button type="button" class="btn btn-reject w-100 d-flex justify-content-center align-items-center gap-1" onclick="confirmReject({{ $item->id }}, '{{ $item->peminjam_nama ?? 'Peminjam' }}')">
                                            <i class="bi bi-x-lg"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-5 text-center text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data permohonan peminjaman yang menunggu persetujuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small" id="tableInfo">
                Menampilkan 1 sampai {{ count($borrowings ?? []) }} dari {{ count($borrowings ?? []) }} data
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link text-muted" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link bg-primary border-primary" href="#">1</a></li>
                    <li class="page-item disabled"><a class="page-link text-muted" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- MODAL SMART QR CODE GENERATOR (VERSI ANTI-BENTROK)      -->
@if(in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'pic']))
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

<!-- BOOTSTRAP JS & SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // FUNGSI AUTO GENERATE CODE DARI DATABASE
    function generateAutoCode(modalElement) {
        const inputField = modalElement.querySelector('.qr-input-code-target');
        if (!inputField) return;

        // Mengambil kode selanjutnya dari server (Laravel)
        fetch("{{ route('borrowing.next-code') }}")
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.next_code) {
                    inputField.value = data.next_code; // Mengisi otomatis tanpa diketik
                    
                    // Memaksa sistem membaca inputan baru untuk mengganti gambar QR
                    inputField.dispatchEvent(new Event('input', { bubbles: true }));
                }
            })
            .catch(error => console.error('Gagal memuat kode otomatis:', error));
    }

    document.addEventListener("DOMContentLoaded", function() {
        // ================================================================
        // SCRIPT REAL-TIME AUTO REFRESH QR CODE (ANTI-BENTROK)
        // ================================================================
        document.body.addEventListener('input', function(e) {
            if (e.target && e.target.classList.contains('qr-input-code-target')) {
                let code = e.target.value.trim();
                if (code === '') {
                    code = 'PLN-01'; // Default jika dikosongkan secara paksa
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

        // Memicu fungsi generate otomatis SATU KALI saat modal baru saja selesai dibuka
        document.body.addEventListener('shown.bs.modal', function(e) {
            if (e.target && e.target.classList.contains('smart-qr-modal-container')) {
                generateAutoCode(e.target);
            }
        });

        // ================================================================
        // SCRIPT PENCARIAN TABEL 
        // ================================================================
        const searchInput = document.getElementById('searchInput');
        if(searchInput) {
            searchInput.addEventListener('keyup', function () {
                const filter = this.value.toLowerCase();
                // Mencari semua baris di dalam tabel dengan class table-custom
                document.querySelectorAll('.table-custom tbody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
                });
            });
        }
    });

    // ================================================================
    // SCRIPT SWEETALERT (TOLAK, SETUJUI, HAPUS) TETAP BERFUNGSI
    function confirmApprove(id, name) {
        Swal.fire({
            title: 'Setujui Peminjaman?',
            text: `Apakah Anda yakin ingin menyetujui peminjaman dari ${name}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: ' Ya, Setujui',
            cancelButtonText: ' Tidak',
            reverseButtons: true,
            customClass: { popup: 'rounded-4 shadow-lg' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', icon: 'success', showConfirmButton: false, timer: 1200, willClose: () => { document.getElementById(`approve-form-${id}`).submit(); }});
            }
        });
    }

    function confirmReject(id, name) {
        Swal.fire({
            title: 'Tolak Peminjaman?',
            text: `Apakah Anda yakin ingin menolak permohonan peminjaman dari ${name}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: ' Ya, Tolak',
            cancelButtonText: ' Tidak',
            reverseButtons: true,
            customClass: { popup: 'rounded-4 shadow-lg' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', icon: 'error', showConfirmButton: false, timer: 1200, willClose: () => { document.getElementById(`reject-form-${id}`).submit(); }});
            }
        });
    }

    function confirmDeleteBorrowing(id, name) {
        Swal.fire({
            title: 'Hapus Data?',
            text: `Hapus data peminjaman dari ${name}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }

    function confirmDeleteK3(id, docNumber) {
        Swal.fire({
            title: 'Hapus Dokumen?',
            text: `Hapus dokumen K3 ${docNumber}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-k3-form-${id}`).submit();
            }
        });
    }
</script>
</body>
</html>