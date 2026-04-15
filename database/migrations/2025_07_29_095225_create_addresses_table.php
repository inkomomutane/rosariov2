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
        Schema::create('addresses', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('policeman_id');
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('locality')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('block')->nullable();
            $table->string('cell')->nullable();
            $table->string('street_or_nearby')->nullable();
            $table->string('city')->nullable();
            $table->string('circle')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
