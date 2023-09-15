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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('street')->nullable();
            $table->string('brgy')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->enum('type', ['outpatient','admitted_patient'])->default('outpatient');
            $table->string('admitted_date')->nullable();
            $table->string('discharged_date')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->smallInteger('room_number')->nullable();
            $table->smallInteger('bed_number')->nullable();
            $table->smallInteger('physician')->nullable();
            $table->string('medical_condition')->nullable();
            $table->string('diagnosis')->nullable();
            $table->string('medication')->nullable();
            $table->string('guardian_first_name')->nullable();
            $table->string('guardian_last_name')->nullable();
            $table->string('guardian_birthdate')->nullable();
            $table->string('relationship')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
