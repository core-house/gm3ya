# دليل إعداد صفحة الأدمن

## نظرة عامة
صفحة الأدمن تسمح بإنشاء وإدارة Tenants في النظام. محمية بكلمة مرور بسيطة.

## الإعداد

### 1. إضافة كلمة المرور في .env
أضف السطر التالي في ملف `.env`:
```env
ADMIN_PASSWORD=admin123
```

**ملاحظة مهمة:** يمكنك تغيير كلمة المرور لأي قيمة تريدها. الكلمة الافتراضية هي `admin123`.

### 2. الوصول لصفحة الأدمن
- URL: `/admin/login`
- كلمة المرور: القيمة الموجودة في `ADMIN_PASSWORD` في `.env`

## المميزات

### لوحة التحكم
- عرض جميع Tenants
- إحصائيات (إجمالي Tenants، النشطة، غير النشطة)
- عدد المستخدمين لكل Tenant

### إدارة Tenants
- ✅ إنشاء Tenant جديد
- ✅ تعديل Tenant موجود
- ✅ حذف Tenant
- ✅ عرض حالة Tenant (نشط، غير نشط، معلق)

## Routes

- `GET /admin/login` - صفحة تسجيل الدخول
- `POST /admin/login` - تسجيل الدخول
- `GET /admin/dashboard` - لوحة التحكم
- `GET /admin/tenants/create` - إنشاء Tenant جديد
- `POST /admin/tenants` - حفظ Tenant جديد
- `GET /admin/tenants/{tenant}/edit` - تعديل Tenant
- `PUT /admin/tenants/{tenant}` - تحديث Tenant
- `DELETE /admin/tenants/{tenant}` - حذف Tenant
- `POST /admin/logout` - تسجيل الخروج

## الأمان

- صفحة الأدمن محمية بـ `AdminMiddleware`
- كلمة المرور مخزنة في `.env` (يُنصح بتغييرها في الإنتاج)
- Session-based authentication (لا يحتاج user account)

## ملاحظات

- صفحة الأدمن منفصلة تماماً عن نظام authentication العادي
- لا تحتاج إلى user account للوصول للأدمن
- يمكن الوصول للأدمن من أي مكان (لا يحتاج tenant)

