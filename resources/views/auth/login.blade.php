<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            /* Latar belakang gelap penuh satu layar */
            background: url('{{ asset("images/bg-login.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }
        /* Efek gelap transparan di atas background agar teks terbaca jelas */
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(30, 30, 30, 0.75); 
            z-index: 0;
        }
        .login-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        /* Kotak Form Transparan */
        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.85); /* Warna putih transparan */
            backdrop-filter: blur(10px); /* Efek kaca blur */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        /* Judul Header di atas card */
        .app-header {
            color: #ffffff;
            text-align: center;
            margin-bottom: 25px;
            z-index: 1;
        }

        /* --- CSS UNTUK MENGHILANGKAN GARIS/KOTAK BIRU SAAT DI-KLIK --- */
        .form-control:focus, 
        .btn:focus,
        .input-group-text:focus {
            border-color: #ced4da !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* --- MENGHILANGKAN IKON MATA BAWAAN BROWSER (EDGE / CHROMIUM) --- */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }
    </style>
</head>
<body>

<div class="login-container">
    
    <!-- Teks Judul Atas -->
    <div class="app-header">
        <h3 class="fw-bold tracking-wide mb-1" style="letter-spacing: 2px;">K3 & PEMINJAMAN</h3>
    </div>

    <!-- Kotak Card Login Transparan -->
    <div class="login-card">
        
        <!-- Logo PLN / ICON+ -->
        <div class="text-center mb-3">
            <img src="{{ asset('images/logo-pln.png') }}" alt="Logo PLN" class="mx-auto d-block" style="max-height: 100px; width: auto;">
        </div>

        <div class="text-center mb-4">
            <h5 class="fw-bold text-dark mb-1">Login to your account</h5>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 small">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="Username" value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="password" name="password" placeholder="Password" required>
                    <button class="btn btn-white border border-start-0 text-muted bg-white" type="button" id="togglePassword">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-success py-2 fw-bold shadow-sm" style="background-color: #28a745; border: none;">
                    Login →
                </button>
            </div>
        </form>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });
</script>
</body>
</html>