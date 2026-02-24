<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('association_members', function (Blueprint $table) {
            $table->decimal('insurance_amount', 10, 2)->default(0)->after('collection_status');
            $table->enum('insurance_status', ['pending', 'paid', 'returned'])->default('pending')->after('insurance_amount');
            $table->date('insurance_paid_date')->nullable()->after('insurance_status');
        });
    }

    public function down(): void
    {
        Schema::table('association_members', function (Blueprint $table) {
            $table->dropColumn(['insurance_amount', 'insurance_status', 'insurance_paid_date']);
        });
    }
};
