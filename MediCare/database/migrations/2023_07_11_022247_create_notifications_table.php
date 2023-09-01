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
            $table->smallInteger('account_id');
            $table->string('title');
            $table->text('message');
            $table->string('date');
            $table->string('time');
            $table->boolean('is_read')->default(false);
            $table->string('specialties')->nullable();
            $table->enum('type', ['user','nurse','doctor','admin',''])->default('');
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
