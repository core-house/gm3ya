<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التسجيل</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .register-container { background: #fff; padding: 40px; border: 4px solid #000; max-width: 400px; width: 100%; }
        h1 { margin-bottom: 30px; font-weight: 900; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 900; margin-bottom: 8px; }
        input, select { width: 100%; padding: 12px; border: 3px solid #000; font-size: 16px; }
        .btn { width: 100%; padding: 12px; background: #000; color: #fff; border: 3px solid #000; 
               font-weight: 900; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .btn:hover { background: #fff; color: #000; }
        .alert { padding: 15px; margin-bottom: 20px; border: 4px solid #000; font-weight: bold; }
        .alert-error { background: #f00; color: #fff; }
        .alert-success { background: #0f0; }
        .login-link { text-align: center; margin-top: 20px; }
        .login-link a { color: #000; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>التسجيل</h1>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">الاسم</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="phone">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
            </div>

            <div class="form-group">
                <label for="tenant_id">الـ Tenant</label>
                <select id="tenant_id" name="tenant_id" required>
                    <option value="">اختر الـ Tenant</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">تأكيد كلمة المرور</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn">التسجيل</button>
        </form>

        <div class="login-link">
            <a href="{{ route('login') }}">لديك حساب بالفعل؟ سجل دخول</a>
        </div>
    </div>
</body>
</html>

