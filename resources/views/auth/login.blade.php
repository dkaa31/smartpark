<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPark - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .login-wrap { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .logo-box { width: 52px; height: 52px; background: #1e3a8a; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem; font-weight: 700; }
        .login-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px 36px; width: 100%; max-width: 420px; }
        .btn-primary { background: #1e3a8a; border-color: #1e3a8a; border-radius: 6px; font-weight: 600; }
        .btn-primary:hover { background: #1e40af; border-color: #1e40af; }
        .form-control { border-radius: 6px; font-size: 0.9rem; }
        .form-control:focus { border-color: #1e3a8a; box-shadow: 0 0 0 3px rgba(30,58,138,.1); }
        .footer-text { font-size: 0.7rem; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; margin-top: 24px; }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="text-center mb-4">
        <img src="{{ asset('image/logo.png') }}" alt="SmartPark Logo" style="height:52px;width:52px;object-fit:contain;border-radius:12px;" class="mb-3">
        <h5 class="fw-bold mb-1" style="color:#111827;">SmartPark</h5>
        <p class="text-muted mb-0" style="font-size:.875rem;">Sistem Manajemen Parkir</p>
    </div>

    <div class="login-card">
       <h6 class="fw-bold mb-1 text-center" style="color:#111827;">Halaman Masuk</h6>
<p class="text-muted mb-4 text-center" style="font-size:.70rem;">Silakan masukkan Username & Password Anda untuk melanjutkan.</p>

        @if($errors->any())
            <div class="alert alert-danger py-2 px-3" style="font-size:.85rem;border-radius:6px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.875rem;">Nama Pengguna</label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                    placeholder="Masukkan nama pengguna" value="{{ old('username') }}" required autofocus>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label fw-semibold mb-0" style="font-size:.875rem;">Kata Sandi</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1"
                        style="border-color:#dee2e6;border-radius:0 6px 6px 0;">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:.875rem;">Tetap masuk</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
        </form>

        <hr class="my-3">
        <p class="text-center text-muted mb-0" style="font-size:.8rem;">
            Butuh bantuan? <a href="#" style="color:#1e3a8a;">Hubungi Dukungan</a>
        </p>
    </div>

    <p class="footer-text">© {{ date('Y') }} Portal Administrasi SmartPark</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
</script>
</body>
</html>
