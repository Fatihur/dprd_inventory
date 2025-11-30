<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris DPRD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('{{ asset('bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            padding: 40px 35px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header .icon-circle {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        .login-header .icon-circle i {
            font-size: 1.8rem;
            color: #fff;
        }
        .login-header h4 {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
            font-size: 1.3rem;
        }
        .login-header p {
            color: #718096;
            font-size: 0.85rem;
            font-weight: 300;
        }
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        .form-label {
            font-weight: 500;
            color: #4a5568;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 500;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(102, 126, 234, 0.4);
        }
        .form-check-label {
            color: #718096;
            font-size: 0.85rem;
        }
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
        .divider span {
            padding: 0 15px;
            color: #a0aec0;
            font-size: 0.8rem;
        }
        .demo-section {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .demo-chip {
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            background: #f7fafc;
            color: #4a5568;
        }
        .demo-chip:hover {
            background: #667eea;
            color: #fff;
            border-color: #667eea;
            transform: scale(1.05);
        }
        .demo-chip.active {
            background: #667eea;
            color: #fff;
            border-color: #667eea;
        }
        .alert {
            border: none;
            border-radius: 12px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
               
                <h4>DPRD Inventory</h4>
                <p>Sistem Informasi Peminjaman Inventaris</p>
            </div>

            @if($errors->any())
            <div class="alert alert-danger py-2 mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-primary btn-login w-100">
                    Masuk
                </button>
            </form>

            <div class="divider">
                <span>Akun Demo</span>
            </div>
            
            <div class="demo-section">
                <span class="demo-chip" data-email="admin@dprd.go.id" data-password="password">Admin</span>
                <span class="demo-chip" data-email="operator@dprd.go.id" data-password="password">Operator</span>
                <span class="demo-chip" data-email="kabag@dprd.go.id" data-password="password">Kabag</span>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.demo-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                document.getElementById('email').value = this.dataset.email;
                document.getElementById('password').value = this.dataset.password;
                document.querySelectorAll('.demo-chip').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
