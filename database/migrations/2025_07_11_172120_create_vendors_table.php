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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 20);
            $table->string('company_phone', 20)->unique();
            $table->string('email', 20)->nullable(); 
            $table->string('rep_name', 50)->nullable(); 
            $table->string('rep_phone', 20)->nullable(); 
            $table->string('address', 100)->nullable();
            $table->string('logo', 50)->nullable();
            $table->string('website', 20)->nullable();
            $table->string('gst_number', 20)->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor');
    }
};
