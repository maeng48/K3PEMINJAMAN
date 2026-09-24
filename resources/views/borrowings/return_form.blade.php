<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengembalian Barang</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .form-container { 
            max-width: 500px; 
            margin: 40px auto; 
            background: #fff; 
            padding: 25px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h4 class="fw-bold text-success text-center mb-1">Form Pengembalian Barang</h4>
            <p class="text-muted text-center small mb-4">Kode Barang: <strong>{{ $code }}</strong></p>

            <!-- Notifikasi Pesan -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Info Singkat Barang yang Dipinjam -->
            <div class="card mb-3 bg-light border-0">
                <div class="card-body small">
                    <p class="mb-1"><strong>Peminjam:</strong> {{ $borrowing->peminjam_nama ?? '-' }}</p>
                    <p class="mb-1"><strong>Divisi:</strong> {{ $borrowing->divisi ?? '-' }}</p>
                    <p class="mb-0"><strong>Barang:</strong> {{ $borrowing->merk_barang ?? '-' }}</p>
                </div>
            </div>

            <!-- Form Pengembalian Hanya Foto -->
          <!-- Ubah $code menjadi $borrowing->id -->
<form action="{{ route('borrowing.return.process', $borrowing->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="mb-4">
        <label class="form-label fw-bold small">Foto Kondisi Barang Saat Dikembalikan</label>
        <input type="file" name="foto_kembali" class="form-control" required>
        <div class="form-text small">Unggah foto bukti pengembalian barang.</div>
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-success fw-bold py-2">Konfirmasi Pengembalian Barang</button>
    </div>
</form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

   <!-- ==================== BAGIAN QR CODE UNTUK PENGEMBALIAN ==================== -->
    <div class="container">
        <div style="max-width: 500px; margin: 0 auto;">
            <div class="card border-0 shadow-sm rounded-4 mt-3 text-center p-4">
                <h6 class="fw-bold mb-2" style="color: var(--pln-navy);">
                    <i class="bi bi-qr-code-scan me-2 text-info"></i>QR Code Pengembalian Barang
                </h6>
                <p class="text-muted small mb-3">
                    Simpan QR Code di bawah ini untuk melakukan Pengembalian barang.
                </p>
                
                <div class="d-flex justify-content-center">
                    <div class="p-3 bg-white d-inline-block rounded-3 border shadow-sm">
                        <!-- Menghasilkan QR Code secara otomatis berdasarkan URL halaman saat ini -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(url()->current()) }}" alt="QR Code Pengembalian" class="img-fluid" style="max-width: 150px;">
                    </div>
                </div>

                <div class="mt-3">
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
                        Kode Barang: <strong>{{ $code ?? ($borrowing->item_code ?? $borrowing->kode_barang ?? 'PLN-X') }}</strong>
                    </span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>