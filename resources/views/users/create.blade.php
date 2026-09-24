<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Akun Pengguna - K3 System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .navbar-custom { background-color: #0A3A60; border-bottom: 3px solid #00A3E0; }
        .card-header-pln { background-color: #0A3A60 !important; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-custom shadow-sm mb-4 py-3">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">K3 PEMINJAMAN SYSTEM</a>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm fw-bold rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>
</nav>

<div class="container py-4" style="max-width: 600px;">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header card-header-pln py-3 px-4">
            <h5 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Tambah Akun Pengguna Baru</h5>
        </div>
        <div class="card-body p-4 bg-white">
            
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Pilih Peran (Role)</label>
                    <select name="role" class="form-select" required>
                        <option value="" selected disabled>-- Pilih Peran --</option>
                        <option value="admin">Admin</option>
                        <option value="pic">PIC</option>
                        <option value="tl">Team Leader (TL)</option>
                        <option value="manager">Manager</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm" style="background-color: #0A3A60; border: none;">
                        <i class="bi bi-save me-1"></i> Simpan Akun Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>