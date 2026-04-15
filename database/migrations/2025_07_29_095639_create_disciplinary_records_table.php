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
        Schema::create('disciplinary_records', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('policeman_id');


            $table->text('infraction')->nullable();
            $table->text('penalty')->nullable();
            $table->string('organ')->nullable();
            $table->string('process_number')->nullable();
            $table->date('record_date')->nullable();

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
        Schema::dropIfExists('disciplinary_records');
    }
};
