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
        Schema::create('running_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('projection_id')->nullable();
            $table->foreign('projection_id')->references('id')->on('projections')->onDelete('cascade');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->decimal('projection_value', 10, 2);
            $table->enum('type', ['Approval', 'Fabrication'])->default('Approval');
            $table->string('billing_desc')->nullable();
            $table->enum('status', ['Completed', 'In Progress'])->default('In Progress');
            $table->string('remarks')->nullable();
            $table->string('invoice_details')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('running_projects');
    }
};
