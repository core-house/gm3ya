<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('associations', function (Blueprint $table) {
            $table->decimal('insurance_amount', 10, 2)->default(0)->after('total_amount');
            $table->decimal('commission_amount', 10, 2)->default(0)->after('insurance_amount');
            $table->enum('commission_type', ['percentage', 'fixed'])->default('fixed')->after('commission_amount');
        });
    }

    public function down(): void
    {
        Schema::table('associations', function (Blueprint $table) {
            $table->dropColumn(['insurance_amount', 'commission_amount', 'commission_type']);
        });
    }
};
