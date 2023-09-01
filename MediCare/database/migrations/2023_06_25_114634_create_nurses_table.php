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
        Schema::create('nurses', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('account_id')->nullable();
            $table->smallInteger('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('qualification')->nullable();
            $table->enum('shift', ['day','night','rotating shifts'])->default('day');
            $table->date('employment_date')->nullable();
            $table->smallInteger('years_of_experience')->nullable();
            $table->string('phone')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('photo')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurses');
    }
};
