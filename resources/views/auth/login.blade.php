<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-container { background: #fff; padding: 40px; border: 4px solid #000; max-width: 400px; width: 100%; }
        h1 { margin-bottom: 30px; font-weight: 900; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 900; margin-bottom: 8px; }
        input { width: 100%; padding: 12px; border: 3px solid #000; font-size: 16px; }
        .btn { width: 100%; padding: 12px; background: #000; color: #fff; border: 3px solid #000; 
               font-weight: 900; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .btn:hover { background: #fff; color: #000; }
        .alert { padding: 15px; margin-bottom: 20px; border: 4px solid #000; font-weight: bold; }
        .alert-error { background: #f00; color: #fff; }
        .alert-success { background: #0f0; }
        .remember-me { display: flex; align-items: center; margin: 15px 0; }
        .remember-me input { width: auto; margin-left: 10px; }
        .register-link { text-align: center; margin-top: 20px; }
        .register-link a { color: #000; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>تسجيل الدخول</h1>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="phone">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">تذكرني</label>
            </div>

            <button type="submit" class="btn">تسجيل الدخول</button>
        </form>

        <div class="register-link">
            <a href="{{ route('register') }}">ليس لديك حساب؟ سجل الآن</a>
        </div>
    </div>
</body>
</html>

