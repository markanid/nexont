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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->tinyInteger('is_approved')->nullable()->default(0)->comment('0=Pending, 1=Approved, 2=Rejected');
            
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity');
    }
};
