<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['رقم الهاتف أو كلمة المرور غير صحيحة.'],
            ]);
        }

        // التحقق من أن الـ tenant نشط
        if ($user->tenant_id) {
            $tenant = $user->tenant;
            if (!$tenant || !$tenant->isActive()) {
                throw ValidationException::withMessages([
                    'phone' => ['لا يمكن تسجيل الدخول: الـ tenant غير نشط.'],
                ]);
            }
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        $tenants = Tenant::where('status', 'active')->get();
        return view('auth.register', compact('tenants'));
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'tenant_id' => 'required|exists:tenants,id',
            'tenant_id' => 'string',
        ]);

        // إنشاء email فريد من رقم الهاتف والـ tenant_id
        // تنظيف رقم الهاتف من الأحرف الخاصة
        $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
        $email = $cleanPhone . '_' . $request->tenant_id . '@example.com';
        
        // التأكد من عدم تكرار email
        $emailExists = User::where('email', $email)->exists();
        if ($emailExists) {
            $email = $cleanPhone . '_' . $request->tenant_id . '_' . time() . '@example.com';
        }
        
        // إنشاء المستخدم مع التأكد من إرسال email
        // استخدام fill() و save() لضمان إرسال جميع الحقول
        $user = new User();
        $user->name = $request->name;
        $user->email = $email; // تعيين مباشر لضمان الإرسال
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->tenant_id = $request->tenant_id;
        $user->save();

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'تم التسجيل بنجاح!');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
