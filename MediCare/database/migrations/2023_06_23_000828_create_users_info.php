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
        Schema::create('users_info', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('account_id')->nullable();
            $table->string('gender');
            $table->smallInteger('age');
            $table->string('phone')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('photo')->nullable();
            $table->string('address')->nullable();
            $table->string('occupation')->nullable();
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_info');
    }
};
