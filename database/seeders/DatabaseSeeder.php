<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // إنشاء tenant تجريبي
        $tenant = Tenant::firstOrCreate(
            ['name' => 'Test Tenant'],
            [
                'domain' => 'test.local',
                'subdomain' => 'test',
                'status' => 'active',
            ]
        );

        // إنشاء مستخدم مرتبط بالـ tenant
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '01000000000',
            'password' => Hash::make('12345678'),
            'tenant_id' => $tenant->id,
        ]);
    }
}
