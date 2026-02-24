# دليل إعداد Multi-Tenancy

## نظرة عامة
تم تحويل المشروع إلى نظام Multi-Tenant حيث كل tenant له بيانات منفصلة تماماً.

## البنية

### 1. جدول Tenants
- `id`: معرف الـ tenant
- `name`: اسم الـ tenant
- `domain`: النطاق (اختياري)
- `subdomain`: النطاق الفرعي (اختياري)
- `settings`: إعدادات JSON
- `status`: الحالة (active, inactive, suspended)

### 2. Tenant ID في الجداول
تم إضافة `tenant_id` لجميع الجداول التالية:
- users
- clients
- associations
- safes
- loans
- deposits
- debts
- payments
- receipts
- transactions
- commissions
- penalties
- association_members

### 3. BelongsToTenant Trait
جميع الـ Models (ما عدا Tenant و User) تستخدم `BelongsToTenant` trait الذي:
- يطبق Global Scope تلقائياً لتصفية البيانات حسب tenant_id
- يضيف tenant_id تلقائياً عند الإنشاء
- يوفر علاقة `tenant()` و scope `forTenant()`

### 4. TenantMiddleware
- يتحقق من أن المستخدم مرتبط بـ tenant
- يتحقق من أن الـ tenant نشط
- يمنع الوصول إذا لم يكن هناك tenant صالح

## الإعداد

### 1. تشغيل Migrations
```bash
php artisan migrate
```

### 2. إنشاء Tenant تجريبي
```bash
php artisan db:seed --class=TenantSeeder
```

سيتم إنشاء:
- Tenant باسم "Tenant Demo"
- مستخدم: admin@demo.com / password

### 3. إنشاء Tenants جديدة
يمكنك إنشاء tenants جديدة عبر:
- TenantController (إذا كان لديك واجهة)
- Tinker: `php artisan tinker`
- Seeder مخصص

## الاستخدام

### إنشاء Tenant جديد
```php
$tenant = Tenant::create([
    'name' => 'New Tenant',
    'domain' => 'newtenant.local',
    'status' => 'active',
]);

$user = User::create([
    'name' => 'Admin',
    'email' => 'admin@newtenant.com',
    'password' => Hash::make('password'),
    'tenant_id' => $tenant->id,
]);
```

### الوصول للبيانات
جميع الاستعلامات تلقائياً تصفى حسب tenant_id للمستخدم المسجل دخول:
```php
// هذا يعيد فقط clients للـ tenant الحالي
$clients = Client::all();

// نفس الشيء
$associations = Association::where('status', 'active')->get();
```

### الوصول لجميع Tenants (Super Admin)
إذا كنت تحتاج للوصول لجميع الـ tenants (لإدارة النظام):
```php
// في TenantController أو أي مكان يحتاج super admin
Tenant::withoutGlobalScope('tenant')->get();
```

### إنشاء بيانات جديدة
عند إنشاء أي model، tenant_id يضاف تلقائياً:
```php
$client = Client::create([
    'name' => 'New Client',
    'phone' => '01234567890',
    // tenant_id يضاف تلقائياً
]);
```

## ملاحظات مهمة

1. **User Model**: يجب أن يكون لكل user `tenant_id` قبل تسجيل الدخول
2. **Global Scope**: يعمل تلقائياً على جميع الاستعلامات والعلاقات
3. **Isolation**: البيانات معزولة تماماً بين الـ tenants
4. **Middleware**: TenantMiddleware يعمل على جميع routes في web group

## التطوير المستقبلي

- إضافة Super Admin يمكنه الوصول لجميع الـ tenants
- إضافة Tenant switching للمستخدمين متعددي الـ tenants
- إضافة واجهة إدارة للـ tenants
- إضافة subdomain routing (اختياري)

