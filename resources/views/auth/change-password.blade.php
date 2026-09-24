<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password - K3 System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">

<div class="container" style="max-width: 450px;">
    <div class="card shadow-sm border-0 p-4">
        <h4 class="fw-bold text-center mb-1 text-primary"> Ubah Password</h4>
        <p class="text-muted small text-center mb-4">Silakan masukkan password lama dan password baru Anda.</p>

        @if(session('error'))
            <div class="alert alert-danger py-2 small mb-3">{{ session('error') }}</div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Password Saat Ini</label>
                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password Baru</label>
                <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                @error('new_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-2">Simpan Password Baru</button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100 fw-bold py-2">Batal</a>
        </form>
    </div>
</div>
</body>
</html>                     