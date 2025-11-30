<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris DPRD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #3c4b64 0%, #1a2332 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
        }
        .login-header {
            background: #3c4b64;
            padding: 30px;
            text-align: center;
            color: #fff;
        }
        .login-header h4 {
            margin: 0;
            font-weight: 600;
        }
        .login-header p {
            margin: 5px 0 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }
        .login-body {
            padding: 30px;
        }
        .form-floating > .form-control {
            border-radius: 8px;
        }
        .btn-login {
            background: #321fdb;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            background: #2819b0;
        }
        .demo-btn {
            text-align: left;
            transition: all 0.2s;
        }
        .demo-btn:hover, .demo-btn.active {
            transform: translateX(5px);
        }
        .demo-btn.active {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-building" style="font-size: 2.5rem;"></i>
            <h4 class="mt-2">DPRD Kabupaten Sumbawa</h4>
            <p>Sistem Informasi Peminjaman Inventaris</p>
        </div>
        <div class="login-body">
            @if($errors->any())
            <div class="alert alert-danger py-2">
                <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                    <label for="email"><i class="bi bi-envelope me-2"></i>Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-primary btn-login w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>

            <hr class="my-4">
            <div class="demo-accounts">
                <p class="text-muted text-center mb-3"><small><i class="bi bi-info-circle me-1"></i>Akun Demo (Klik untuk mengisi form)</small></p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-danger btn-sm demo-btn" data-email="admin@dprd.go.id" data-password="password">
                        <i class="bi bi-shield-lock me-2"></i>Administrator
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm demo-btn" data-email="operator@dprd.go.id" data-password="password">
                        <i class="bi bi-person-gear me-2"></i>Operator
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm demo-btn" data-email="kabag@dprd.go.id" data-password="password">
                        <i class="bi bi-person-check me-2"></i>Kepala Bagian Umum
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.demo-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('email').value = this.dataset.email;
                document.getElementById('password').value = this.dataset.password;
                document.querySelectorAll('.demo-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
