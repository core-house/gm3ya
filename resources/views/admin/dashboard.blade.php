<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الأدمن - Tenants</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #1a1a1a; color: #e0e0e0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { background: #2d2d2d; padding: 20px; border: 2px solid #4af; border-radius: 10px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        h1 { color: #4af; font-weight: 900; }
        .btn { padding: 12px 24px; text-decoration: none; cursor: pointer; font-weight: 900; margin: 5px; border-radius: 5px; display: inline-block; border: 2px solid; transition: all 0.3s; }
        .btn-primary { background: #4af; color: #000; border-color: #4af; }
        .btn-primary:hover { background: #6cf; border-color: #6cf; }
        .btn-danger { background: #d22; color: #fff; border-color: #d22; }
        .btn-danger:hover { background: #f44; border-color: #f44; }
        .btn-success { background: #2d5; color: #fff; border-color: #2d5; }
        .btn-success:hover { background: #4f4; border-color: #4f4; }
        .btn-secondary { background: #444; color: #e0e0e0; border-color: #444; }
        .btn-secondary:hover { background: #555; border-color: #555; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0; }
        .stat-box { background: #2d2d2d; padding: 20px; border: 2px solid #444; border-radius: 10px; text-align: center; }
        .stat-box h3 { font-size: 32px; color: #4af; margin: 10px 0; }
        .stat-box p { color: #aaa; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; background: #2d2d2d; border: 2px solid #444; border-radius: 10px; overflow: hidden; }
        th, td { padding: 15px; text-align: right; border-bottom: 1px solid #444; }
        th { background: #383838; color: #4af; font-weight: 900; }
        tr:hover { background: #333; }
        .badge { display: inline-block; padding: 5px 10px; font-weight: bold; border-radius: 5px; }
        .badge-active { background: #2d5; color: #fff; }
        .badge-inactive { background: #555; color: #fff; }
        .badge-suspended { background: #d22; color: #fff; }
        .alert { padding: 15px; margin: 20px 0; border: 2px solid; border-radius: 5px; font-weight: bold; }
        .alert-success { background: #2d5; color: #fff; border-color: #2d5; }
        .alert-error { background: #d22; color: #fff; border-color: #d22; }
        .actions { display: flex; gap: 10px; }
        .actions form { display: inline; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🔐 لوحة تحكم الأدمن - إدارة Tenants</h1>
            <div>
                <a href="{{ route('admin.create-tenant') }}" class="btn btn-primary">➕ إضافة Tenant جديد</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-secondary">تسجيل الخروج</button>
                </form>
            </div>
        </header>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="stats">
            <div class="stat-box">
                <p>إجمالي Tenants</p>
                <h3>{{ $totalTenants }}</h3>
            </div>
            <div class="stat-box">
                <p>Tenants النشطة</p>
                <h3>{{ $activeTenants }}</h3>
            </div>
            <div class="stat-box">
                <p>Tenants غير النشطة</p>
                <h3>{{ $totalTenants - $activeTenants }}</h3>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>الاسم</th>
                    <th>Domain</th>
                    <th>Subdomain</th>
                    <th>الحالة</th>
                    <th>عدد المستخدمين</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $tenant)
                    <tr>
                        <td>{{ $tenant->id }}</td>
                        <td><strong>{{ $tenant->name }}</strong></td>
                        <td>{{ $tenant->domain ?? '-' }}</td>
                        <td>{{ $tenant->subdomain ?? '-' }}</td>
                        <td>
                            @if($tenant->status === 'active')
                                <span class="badge badge-active">نشط</span>
                            @elseif($tenant->status === 'inactive')
                                <span class="badge badge-inactive">غير نشط</span>
                            @else
                                <span class="badge badge-suspended">معلق</span>
                            @endif
                        </td>
                        <td>{{ $tenant->users_count }}</td>
                        <td>{{ $tenant->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.edit-tenant', $tenant) }}" class="btn btn-success" style="padding: 8px 16px; font-size: 14px;">تعديل</a>
                                <form method="POST" action="{{ route('admin.delete-tenant', $tenant) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا الـ tenant؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 8px 16px; font-size: 14px;">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #aaa;">
                            لا توجد tenants حالياً
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>

