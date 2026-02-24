<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الأدمن</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #1a1a1a; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-container { background: #2d2d2d; padding: 40px; border: 3px solid #4af; max-width: 400px; width: 100%; border-radius: 10px; box-shadow: 0 8px 32px rgba(0,0,0,0.3); }
        h1 { margin-bottom: 30px; font-weight: 900; text-align: center; color: #4af; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 900; margin-bottom: 8px; color: #e0e0e0; }
        input { width: 100%; padding: 12px; border: 2px solid #444; background: #1a1a1a; color: #e0e0e0; font-size: 16px; border-radius: 5px; }
        input:focus { outline: none; border-color: #4af; }
        .btn { width: 100%; padding: 12px; background: #4af; color: #000; border: 2px solid #4af; 
               font-weight: 900; cursor: pointer; font-size: 16px; margin-top: 10px; border-radius: 5px; }
        .btn:hover { background: #6cf; border-color: #6cf; }
        .alert { padding: 15px; margin-bottom: 20px; border: 2px solid #444; font-weight: bold; border-radius: 5px; }
        .alert-error { background: #d22; color: #fff; border-color: #d22; }
        .alert-success { background: #2d5; color: #fff; border-color: #2d5; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #4af; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🔐 تسجيل دخول الأدمن</h1>

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

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required autofocus>
            </div>

            <button type="submit" class="btn">تسجيل الدخول</button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">← العودة لتسجيل الدخول العادي</a>
        </div>
    </div>
</body>
</html>

