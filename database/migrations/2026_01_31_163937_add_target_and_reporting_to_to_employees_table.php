<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('target', 10, 2)->default(0)->after('designation');
            $table->unsignedBigInteger('reporting_to')->nullable()->after('target');

            // self foreign key (employee reports to another employee)
            $table->foreign('reporting_to')
                  ->references('id')
                  ->on('employees')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['reporting_to']);
            $table->dropColumn(['target', 'reporting_to']);
        });
    }
};
