<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pengembalian Barang - K3 System</title>
    
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

        .info-card { 
            max-width: 520px; 
            margin: 50px auto; 
            background: #ffffff; 
            padding: 35px 30px; 
            border-radius: 20px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.06); 
            text-align: center;
            border: 1px solid #E2E8F0;
        }

        .icon-circle-success {
            width: 80px;
            height: 80px;
            background-color: #E6F4EA;
            color: #10B981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
        }

        .badge-code {
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            color: #0F172A;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
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

<div class="container px-3">
    <div class="info-card">
        <div class="icon-circle-success shadow-sm">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        
        <h4 class="fw-bold mb-2" style="color: var(--pln-navy);">Informasi Pengembalian Sukses</h4>
        <p class="text-muted small mb-4">
            Kode Barang: <span class="badge-code">{{ $code ?? $borrowing->qr_code_id ?? '-' }}</span>
        </p>

        <div class="alert alert-success border-0 shadow-sm p-3 mb-4 text-start rounded-3" style="background-color: #F0FDF4; color: #166534;">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-shield-check fs-5 text-success"></i>
                <h6 class="fw-bold mb-0">Barang Sudah Dikembalikan!</h6>
            </div>
            <p class="small mb-0 mt-2 text-muted">
                <strong>Peminjam:</strong> {{ $borrowing->peminjam_nama ?? '-' }}<br>
                <strong>Divisi:</strong> {{ $borrowing->divisi ?? '-' }}
            </p>
        </div>

        <div class="d-grid gap-2">
            @if(Route::has('borrowing.scan'))
                <a href="{{ route('borrowing.scan', $code ?? $borrowing->qr_code_id ?? '') }}" class="btn btn-primary fw-bold py-2 rounded-pill shadow-sm" style="background-color: var(--pln-navy); border: none;">
                    <i class="bi bi-qr-code-scan me-1"></i> Scan Ulang / Menu Peminjaman
                </a>
            @endif
            <a href="{{ route('borrowings.returned') }}" class="btn btn-outline-secondary fw-semibold py-2 rounded-pill">
                <i class="bi bi-list-task me-1"></i> Lihat Daftar Pengembalian
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>