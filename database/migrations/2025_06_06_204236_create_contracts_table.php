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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date')->nullable(); 
            $table->integer('status');
            $table->text('description')->nullable(); 
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('contract_type_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('contract_type_id')->references('id')->on('contracttypes');
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
