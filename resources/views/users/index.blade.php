<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna - K3 System</title>
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
        }
        .card-header-pln { 
            background-color: var(--pln-navy) !important; 
            color: white; 
        }
    </style>
</head>
<body>

<!-- NAVBAR UTAMA -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-4 py-3">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">K3 & PEMINJAMAN SYSTEM</a>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm fw-bold rounded-pill px-3">
            <i class="bi bi-house me-1"></i> Kembali ke Dashboard
        </a>
    </div>
</nav>

<div class="container-fluid px-4 py-2">

    <!-- ALERT ERROR VALIDASI -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- FORM TAMBAH AKUN BARU -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header card-header-pln py-3 px-4">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Tambah Akun Baru</h6>
                </div>
                <div class="card-body p-4 bg-white">
                    <form id="create-user-form" action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 13px;">Nama Lengkap</label>
                            <input type="text" name="name" id="input_name" class="form-control" placeholder="Nama lengkap pengguna" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 13px;">Username</label>
                            <input type="text" name="username" id="input_username" class="form-control" placeholder="Username untuk login" value="{{ old('username') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 13px;">Password</label>
                            <input type="password" name="password" id="input_password" class="form-control" placeholder="Minimal 4 karakter" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="font-size: 13px;">Pilih Peran (Role)</label>
                            <select name="role" id="input_role" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Peran --</option>
                                <option value="admin">Admin</option>
                                <option value="pic">PIC</option>
                                <option value="tl">Team Leader (TL)</option>
                                <option value="manager">Manager</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" onclick="confirmResetForm()" class="btn btn-secondary fw-bold py-2 shadow-sm w-50">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </button>
                            <button type="button" onclick="confirmStoreUser()" class="btn btn-primary fw-bold py-2 shadow-sm w-50" style="background-color: var(--pln-navy); border: none;">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TABEL DAFTAR PENGGUNA -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header card-header-pln py-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i> Daftar Akun Pengguna</h6>
                    <span class="badge bg-light text-dark fw-bold px-3 py-2 rounded-pill">Total: {{ count($users ?? []) }} Akun</span>
                </div>
                <div class="card-body p-4 bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 13px; width:100%;">
                            <thead class="table-light text-uppercase text-secondary" style="font-size: 11px;">
                                <tr>
                                    <th class="py-3 ps-3">No</th>
                                    <th class="py-3">Nama Lengkap</th>
                                    <th class="py-3">Username</th>
                                    <th class="py-3">Role</th>
                                    <th class="py-3 text-center pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users ?? [] as $index => $user)
                                    <tr>
                                        <td class="ps-3 fw-semibold text-secondary">{{ $index + 1 }}</td>
                                        <td><strong>{{ $user->name }}</strong></td>
                                        <td><code>{{ $user->username }}</code></td>
                                        <td>
                                            @if($user->role === 'admin')
                                                <span class="badge bg-danger">ADMIN</span>
                                            @elseif($user->role === 'pic')
                                                <span class="badge bg-primary">PIC</span>
                                            @elseif($user->role === 'tl')
                                                <span class="badge bg-warning text-dark">TEAM LEADER</span>
                                            @elseif($user->role === 'manager')
                                                <span class="badge bg-success">MANAGER</span>
                                            @else
                                                <span class="badge bg-secondary">{{ strtoupper($user->role) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-3">
                                            @if($user->id !== auth()->id() && strtolower($user->username) !== 'admin')
                                                <div class="d-flex justify-content-center gap-3">
                                                    <!-- Tombol Edit Trigger Modal -->
                                                    <button type="button" class="btn btn-sm btn-outline-warning py-1 px-3 shadow-sm" title="Edit Pengguna" 
                                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <!-- Tombol Hapus dengan Konfirmasi SweetAlert2 -->
                                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')" class="btn btn-sm btn-outline-danger py-1 px-3 shadow-sm" title="Hapus Pengguna">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- MODAL EDIT PENGGUNA -->
                                                <div class="modal fade text-start" id="editModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                                            <div class="modal-header text-white" style="background-color: var(--pln-navy);">
                                                                <h5 class="modal-title fw-bold fs-6"><i class="bi bi-pencil-square me-2"></i> Edit Pengguna: {{ $user->name }}</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form id="update-form-{{ $user->id }}" action="{{ route('users.update', $user->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body p-4">
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">Nama Lengkap</label>
                                                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">Username</label>
                                                                        <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">Password Baru <small class="text-muted">(Kosongkan jika tidak diubah)</small></label>
                                                                        <input type="password" name="password" class="form-control" placeholder="Minimal 4 karakter">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">Pilih Peran (Role)</label>
                                                                        <select name="role" class="form-select" required>
                                                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                                            <option value="pic" {{ $user->role == 'pic' ? 'selected' : '' }}>PIC</option>
                                                                            <option value="tl" {{ $user->role == 'tl' ? 'selected' : '' }}>Team Leader (TL)</option>
                                                                            <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer bg-light px-4 py-3">
                                                                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="button" onclick="confirmUpdate('{{ $user->id }}')" class="btn btn-primary btn-sm rounded-pill px-3" style="background-color: var(--pln-navy); border: none;">Simpan Perubahan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif(strtolower($user->username) === 'admin')
                                                <span class="badge bg-secondary px-2 py-1" style="font-size: 11px;"><i class="bi bi-shield-lock-fill me-1"></i> Protected</span>
                                            @else
                                                <span class="text-muted" style="font-size: 11px;">(Akun Anda)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada akun pengguna terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- BOOTSTRAP JS & SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 1800,
        timerProgressBar: true
    });
</script>
@endif

<script>
    // PERBAIKAN: Fungsi Enter untuk melompat ke form berikutnya
    document.addEventListener('DOMContentLoaded', function () {
        const formElements = document.querySelectorAll('#create-user-form input[type="text"], #create-user-form input[type="password"], #create-user-form select');

        formElements.forEach((element, index) => {
            element.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Mencegah submit form bawaan
                    
                    const nextElement = formElements[index + 1];
                    if (nextElement) {
                        nextElement.focus();
                    }
                }
            });
        });
    });

    // Popup Konfirmasi Simpan Akun Baru
    function confirmStoreUser() {
        const name = document.getElementById('input_name').value.trim();
        const username = document.getElementById('input_username').value.trim();
        const password = document.getElementById('input_password').value.trim();
        const role = document.getElementById('input_role').value;

        if (!name || !username || !password || !role) {
            Swal.fire({
                icon: 'error',
                title: 'Form Belum Lengkap',
                text: 'Harap isi semua kolom terlebih dahulu sebelum menyimpan akun!',
                confirmButtonColor: '#0A3A60'
            });
            return;
        }

        Swal.fire({
            title: 'Simpan Akun Baru?',
            text: `Akun dengan username "${username}" akan ditambahkan ke dalam sistem.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0A3A60',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('create-user-form').submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    icon: 'info',
                    title: 'Dibatalkan',
                    text: 'Pembuatan akun dibatalkan.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }

    // Popup Konfirmasi Batal / Reset Form Tambah Akun
    function confirmResetForm() {
        Swal.fire({
            title: 'Batalkan Pengisian?',
            text: "Formulir tambah akun akan dikosongkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6c757d',
            cancelButtonColor: '#0A3A60',
            confirmButtonText: 'Ya, Kosongkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('create-user-form').reset();
                Swal.fire({
                    icon: 'info',
                    title: 'Dibatalkan',
                    text: 'Formulir batal diisi.',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    icon: 'info',
                    title: 'Dibatalkan',
                    text: 'Pengisian formulir dilanjutkan.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }

    // Popup Konfirmasi Hapus Pengguna
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: `Akun "${userName}" akan dihapus secara permanen dari sistem!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus Pengguna',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + userId).submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    icon: 'info',
                    title: 'Dibatalkan',
                    text: 'Data pengguna batal dihapus.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }

    // Popup Konfirmasi Simpan Perubahan (Edit)
    function confirmUpdate(userId) {
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Data akun pengguna akan diperbarui.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0A3A60',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('update-form-' + userId).submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    icon: 'info',
                    title: 'Dibatalkan',
                    text: 'Perubahan data dibatalkan.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }
</script>
</body>
</html>