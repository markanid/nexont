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
            $table->string('project_cid', 50)->nullable();
            $table->string('po', 100)->nullable();

            $table->integer('apr_main_steel')->nullable();
            $table->integer('apr_misc_steel')->nullable();

            $table->decimal('po_main_sd', 10, 2)->nullable();
            $table->decimal('po_misc_sd', 10, 2)->nullable();
            $table->decimal('po_engineering', 10, 2)->nullable();
            
            $table->string('po_currency', 50)->nullable();

            $table->decimal('kitty', 10, 2)->nullable();
            $table->decimal('covalue', 10, 2)->nullable();

            $table->enum('status', ['Planned', 'On Going', 'Completed', 'On Hold', 'Cancelled'])->default('Planned');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('project_manager_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('sales_manager_id')->references('id')->on('employees')->onDelete('set null');
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
