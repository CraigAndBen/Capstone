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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('account_id')->nullable();
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->boolean('is_read')->default(false);
            $table->string('specialties')->nullable();
            $table->string('diagnose')->nullable();
            $table->enum('type', ['user','nurse','doctor','admin','supply_officer',''])->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
