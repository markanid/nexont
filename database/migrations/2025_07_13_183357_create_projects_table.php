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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code')->unique();
            $table->string('project_name');

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('project_manager_id')->nullable();
            $table->unsignedBigInteger('sales_manager_id')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('project_cost', 15, 2)->default(0.00);

            $table->enum('status', ['Planned', 'On Going', 'Completed', 'On Hold', 'Cancelled'])->default('Planned');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('project_manager_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('sales_manager_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
