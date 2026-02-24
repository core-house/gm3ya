<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $adminPassword = env('ADMIN_PASSWORD', 'admin123');

        // مقارنة مباشرة بكلمة المرور
        if ($request->password !== $adminPassword) {
            return back()->withErrors(['password' => 'كلمة المرور غير صحيحة'])->withInput();
        }

        $request->session()->put('admin_authenticated', true);

        return redirect()->route('admin.dashboard');
    }

    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        $tenants = Tenant::withCount('users')->orderBy('created_at', 'desc')->get();
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', 'active')->count();
        
        return view('admin.dashboard', compact('tenants', 'totalTenants', 'activeTenants'));
    }

    /**
     * Show form to create new tenant.
     */
    public function createTenant()
    {
        return view('admin.create-tenant');
    }

    /**
     * Store new tenant.
     */
    public function storeTenant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255|unique:tenants,domain',
            'subdomain' => 'nullable|string|max:255|unique:tenants,subdomain',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        Tenant::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'تم إنشاء الـ tenant بنجاح');
    }

    /**
     * Show form to edit tenant.
     */
    public function editTenant(Tenant $tenant)
    {
        return view('admin.edit-tenant', compact('tenant'));
    }

    /**
     * Update tenant.
     */
    public function updateTenant(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255|unique:tenants,domain,' . $tenant->id,
            'subdomain' => 'nullable|string|max:255|unique:tenants,subdomain,' . $tenant->id,
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $tenant->update($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'تم تحديث الـ tenant بنجاح');
    }

    /**
     * Delete tenant.
     */
    public function deleteTenant(Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('admin.dashboard')->with('success', 'تم حذف الـ tenant بنجاح');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        return redirect()->route('admin.login')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
