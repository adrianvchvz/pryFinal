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
        Schema::create('zoneassignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zone_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('shift_id');
            $table->unsignedBigInteger('conductor_id');
            $table->timestamps();

            $table->foreign('zone_id')->references('id')->on('zones');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->foreign('conductor_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoneassignments');
    }
};
