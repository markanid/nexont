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
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('app_misc_modeling', 8, 2)->nullable()->after('estimated_hours');
            $table->decimal('app_misc_detailing', 8, 2)->nullable();
            $table->decimal('app_misc_erection', 8, 2)->nullable();
            $table->decimal('app_misc_check_model', 8, 2)->nullable();
            $table->decimal('app_misc_check_det_erec', 8, 2)->nullable();
            $table->decimal('app_misc_total', 8, 2)->nullable();
            $table->text('app_misc_remarks')->nullable();
            $table->decimal('app_main_modeling', 8, 2)->nullable();
            $table->decimal('app_main_detailing', 8, 2)->nullable();
            $table->decimal('app_main_erection', 8, 2)->nullable();
            $table->decimal('app_main_check_model', 8, 2)->nullable();
            $table->decimal('app_main_check_det_erec', 8, 2)->nullable();
            $table->decimal('app_main_total', 8, 2)->nullable();
            $table->text('app_main_remarks')->nullable();
            $table->decimal('fab_misc_modeling', 8, 2)->nullable();
            $table->decimal('fab_misc_detailing', 8, 2)->nullable();
            $table->decimal('fab_misc_erection', 8, 2)->nullable();
            $table->decimal('fab_misc_check_model', 8, 2)->nullable();
            $table->decimal('fab_misc_check_det_erec', 8, 2)->nullable();
            $table->decimal('fab_misc_total', 8, 2)->nullable();
            $table->text('fab_misc_remarks')->nullable();
            $table->decimal('fab_main_modeling', 8, 2)->nullable();
            $table->decimal('fab_main_detailing', 8, 2)->nullable();
            $table->decimal('fab_main_erection', 8, 2)->nullable();
            $table->decimal('fab_main_check_model', 8, 2)->nullable();
            $table->decimal('fab_main_check_det_erec', 8, 2)->nullable();
            $table->decimal('fab_main_total', 8, 2)->nullable();
            $table->text('fab_main_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['misc_modeling', 'misc_detailing', 'misc_erection', 'misc_check_model', 'misc_check_det_erec', 'misc_total', 'misc_remarks', 'main_modeling', 'main_detailing', 'main_erection', 'main_check_model', 'main_check_det_erec', 'main_total', 'main_remarks']);
        });
    }
};
