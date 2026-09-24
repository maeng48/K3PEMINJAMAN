<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Daftar Peminjaman Barang' }} - K3 System</title>

    <!-- BOOTSTRAP 5 & ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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

        .navbar-custom {
            background-color: var(--pln-navy);
            border-bottom: 3px solid var(--pln-cyan);
            padding: 14px 24px;
        }

        /* SHORTCUT NAV BAR */
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

        .badge-status-dipinjam {
            background-color: #F59E0B;
            color: #ffffff;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 0.725rem;
        }

        .badge-status-returned {
            background-color: #10B981;
            color: #ffffff;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 0.725rem;
        }

        .badge-status-approved {
            background-color: #10B981;
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
            font-size: 0.7rem;
            padding: 3px 12px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }

        .btn-photo-pill:hover {
            background-color: #F0F9FF;
            border-color: #0284C7;
            color: #0369A1;
        }

        .btn-action-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #EF4444;
            color: #EF4444;
            background: #ffffff;
            transition: all 0.2s;
        }

        .btn-action-icon:hover {
            background: #EF4444;
            color: #ffffff;
        }

        .table-footer {
            padding: 16px 24px;
            background-color: #ffffff;
            border-top: 1px solid #F1F5F9;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-custom shadow-sm mb-4">
    <div class="container-fluid px-2">
        <span class="navbar-brand fw-bold fs-5 mb-0">K3 PEMINJAMAN SYSTEM</span>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-semibold">
            ← Kembali ke Dashboard
        </a>
    </div>
</nav>

<div class="container-fluid px-4 py-2">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- SHORTCUT QUICK NAVIGATION BAR (HANYA UNTUK ADMIN & PIC) -->
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
                <i class="bi {{ $icon ?? 'bi-box-seam' }} fs-5"></i>
                <h6 class="fw-bold mb-0">{{ $title ?? 'Daftar Barang' }}</h6>
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
                        <th style="width: 11%;">KEPERLUAN</th>
                        <th style="width: 13%;">BARANG / TOOLS</th>
                        <th style="width: 9%;">QR CODE</th>
                        <th style="width: 13%;">TGL & FOTO PINJAM</th>
                        <th style="width: 10%;">STATUS BARANG</th>
                        <th style="width: 12%;">TGL & FOTO KEMBALI</th>
                        <th style="width: 11%;">STATUS APPROVAL</th>
                        <th style="width: 6%;">AKSI</th>
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
                            <td>{{ $item->keperluan ?? '-' }}</td>
                            <td>
                                <strong class="d-block text-dark">{{ $item->merk_barang ?? '-' }}</strong>
                                <small class="text-muted" style="font-size: 11px;">Kategori: {{ $item->kategori_barang ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge-code">{{ $item->qr_code_id ?? 'PLN-01' }}</span>
                                <a href="{{ url('/scan/' . ($item->qr_code_id ?? 'PLN-01')) }}" target="_blank" class="text-decoration-none small d-block mt-1" style="font-size: 11px; color: #00A3E0;">
                                    <i class="bi bi-link-45deg"></i> Link Scan
                                </a>
                            </td>
                            <td>
                                <div class="text-muted small mb-1" style="font-size: 11px;">{{ !empty($item->tgl_pinjam) ? \Carbon\Carbon::parse($item->tgl_pinjam)->format('Y-m-d') : '-' }}</div>
                                @if(!empty($item->foto_pinjam))
                                    <a href="{{ asset('storage/' . str_replace('storage/', '', $item->foto_pinjam)) }}" target="_blank" class="btn-photo-pill">
                                        <i class="bi bi-camera"></i> Foto Peminjaman
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if(strtoupper($item->status ?? '') === 'DIKEMBALIKAN')
                                    <span class="badge-status-returned">DIKEMBALIKAN</span>
                                @else
                                    <span class="badge-status-dipinjam">DIPINJAM</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($item->tgl_kembali))
                                    <div class="text-muted small mb-1" style="font-size: 11px;">{{ \Carbon\Carbon::parse($item->tgl_kembali)->format('Y-m-d') }}</div>
                                    @if(!empty($item->foto_kembali))
                                        <a href="{{ asset('storage/' . str_replace('storage/', '', $item->foto_kembali)) }}" target="_blank" class="btn-photo-pill">
                                            <i class="bi bi-camera"></i> Bukti Kembali
                                        </a>
                                    @endif
                                @else
                                    <span class="text-muted small" style="font-size: 11px;">Belum Kembali</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-status-approved">{{ strtoupper($item->approval_status ?? 'APPROVED') }}</span>
                            </td>
                            <td>
                                @if(Route::has('borrowings.destroy'))
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('borrowings.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action-icon" onclick="confirmDeleteBorrowing({{ $item->id }}, '{{ $item->peminjam_nama ?? 'Peminjam' }}')" title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn-action-icon" title="Rute Belum Terdaftar" onclick="alert('Route borrowings.destroy belum terdaftar di web.php')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-5 text-center text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data peminjaman barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Menampilkan 1 sampai {{ count($borrowings ?? []) }} dari {{ count($borrowings ?? []) }} data
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link text-muted" href="#">← Previous</a></li>
                    <li class="page-item active"><a class="page-link bg-primary border-primary" href="#">1</a></li>
                    <li class="page-item disabled"><a class="page-link text-muted" href="#">Next →</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- BOOTSTRAP JS & SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // FUNGSI PENCARIAN REAL-TIME
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('#borrowingsTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    });

    // FUNGSI ANIMASI POP-UP CONFIRMATION HAPUS PEMINJAMAN (YA & TIDAK)
    function confirmDeleteBorrowing(id, name) {
        Swal.fire({
            title: 'Hapus Data Peminjaman?',
            text: `Apakah Anda yakin ingin menghapus data peminjaman dari ${name}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="bi bi "></i> Ya, Hapus',
            cancelButtonText: '<i class="bi bi "></i> Tidak',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-4 shadow-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Penghapusan...',
                    text: 'Data peminjaman berhasil dihapus!',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1200,
                    timerProgressBar: true,
                    willClose: () => {
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Dibatalkan',
                    text: 'Jangan ragu-ragu ya!',
                    icon: 'info',
                    timer: 1300,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-4'
                    }
                });
            }
        });
    }
</script>
</body>
</html>