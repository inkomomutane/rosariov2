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
        Schema::create('reintegrations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('policeman_id');
            $table->string('departure_type');
            $table->longText('reason')->nullable();
            $table->string('unit')->nullable();
            $table->string('category')->nullable();
            $table->text('dispatch_report')->nullable();
            $table->date('reintegration_date')->nullable();
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
        Schema::dropIfExists('reintegrations');
    }
};
