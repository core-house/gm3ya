<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء tenant تجريبي
        $tenant = Tenant::create([
            'name' => 'Tenant Demo',
            'domain' => 'demo.local',
            'subdomain' => 'demo',
            'status' => 'active',
            'settings' => [
                'currency' => 'EGP',
                'timezone' => 'Africa/Cairo',
            ],
        ]);

        // إنشاء مستخدم مرتبط بالـ tenant
        User::create([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'phone' => '01234567890',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
        ]);

        $this->command->info('تم إنشاء tenant تجريبي بنجاح!');
        $this->command->info('Phone: 01234567890');
        $this->command->info('Password: password');
    }
}
