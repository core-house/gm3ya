<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('national_id')->nullable()->after('address');
            $table->string('work_place')->nullable()->after('national_id');
            $table->decimal('salary', 10, 2)->nullable()->after('work_place');
            
            // Guarantor info
            $table->string('guarantor_name')->nullable()->after('salary');
            $table->string('guarantor_phone')->nullable()->after('guarantor_name');
            $table->string('guarantor_national_id')->nullable()->after('guarantor_phone');
            $table->text('guarantor_address')->nullable()->after('guarantor_national_id');
            $table->foreignId('guarantor_client_id')->nullable()->constrained('clients')->after('guarantor_address');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['guarantor_client_id']);
            $table->dropColumn([
                'national_id', 'work_place', 'salary',
                'guarantor_name', 'guarantor_phone', 'guarantor_national_id', 
                'guarantor_address', 'guarantor_client_id'
            ]);
        });
    }
};
