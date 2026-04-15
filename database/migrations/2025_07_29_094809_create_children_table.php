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
        Schema::create('children', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('policeman_id');
            $table->string('child_name');
            $table->string('child_sex');
            $table->date('date_of_birth');
            $table->string('occupation')->nullable();
            $table->foreign('policeman_id')->on('users')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
