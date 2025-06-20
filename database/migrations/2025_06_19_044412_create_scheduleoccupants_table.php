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
        Schema::create('scheduleoccupants', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
            $table->unsignedBigInteger("schedule_id");
            $table->unsignedBigInteger("employee_id");
            $table->unsignedBigInteger("employee_type_id");
            $table->foreign("schedule_id")->references("id")->on("schedules");
            $table->foreign("employee_id")->references("id")->on("employees");
            $table->foreign("employee_type_id")->references("id")->on("employeetypes");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduleoccupants');
    }
};
