<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // إذا كان المستخدم مسجل دخول، نتأكد من وجود tenant_id
        if (Auth::check()) {
            $user = Auth::user();
            
            // إذا لم يكن للمستخدم tenant_id، نمنع الوصول
            if (!$user->tenant_id) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'لا يمكن الوصول: المستخدم غير مرتبط بأي tenant');
            }

            // التحقق من أن الـ tenant نشط
            $tenant = $user->tenant;
            if (!$tenant || !$tenant->isActive()) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'لا يمكن الوصول: الـ tenant غير نشط');
            }
        }

        return $next($request);
    }
}
