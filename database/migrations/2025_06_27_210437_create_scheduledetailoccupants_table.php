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
        Schema::create('scheduledetailoccupants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scheduledetail_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamps();
            $table->foreign('scheduledetail_id')->references('id')->on('scheduledetails')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduledetailoccupants');
    }
};
