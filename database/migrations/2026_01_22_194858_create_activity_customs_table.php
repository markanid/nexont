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
        Schema::create('activity_customs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->decimal('time_hours', 10, 2)->default(0.00);

            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_custom');
    }
};
