<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة Tenant جديد</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #1a1a1a; color: #e0e0e0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .card { background: #2d2d2d; padding: 30px; border: 2px solid #4af; border-radius: 10px; }
        h1 { color: #4af; font-weight: 900; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 900; margin-bottom: 8px; color: #e0e0e0; }
        input, select { width: 100%; padding: 12px; border: 2px solid #444; background: #1a1a1a; color: #e0e0e0; font-size: 16px; border-radius: 5px; }
        input:focus, select:focus { outline: none; border-color: #4af; }
        .btn { padding: 12px 24px; text-decoration: none; cursor: pointer; font-weight: 900; margin: 5px; border-radius: 5px; display: inline-block; border: 2px solid; transition: all 0.3s; }
        .btn-primary { background: #4af; color: #000; border-color: #4af; }
        .btn-primary:hover { background: #6cf; border-color: #6cf; }
        .btn-secondary { background: #444; color: #e0e0e0; border-color: #444; }
        .btn-secondary:hover { background: #555; border-color: #555; }
        .alert { padding: 15px; margin-bottom: 20px; border: 2px solid; border-radius: 5px; font-weight: bold; }
        .alert-error { background: #d22; color: #fff; border-color: #d22; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>➕ إضافة Tenant جديد</h1>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.store-tenant') }}">
                @csrf

                <div class="form-group">
                    <label for="name">اسم الـ Tenant *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="domain">Domain (اختياري)</label>
                    <input type="text" id="domain" name="domain" value="{{ old('domain') }}" placeholder="example.com">
                </div>

                <div class="form-group">
                    <label for="subdomain">Subdomain (اختياري)</label>
                    <input type="text" id="subdomain" name="subdomain" value="{{ old('subdomain') }}" placeholder="subdomain">
                </div>

                <div class="form-group">
                    <label for="status">الحالة *</label>
                    <select id="status" name="status" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>معلق</option>
                    </select>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">إنشاء Tenant</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

