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
        Schema::create('doctors', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('person_id');
            $table->date('registration_date')->nullable();
            $table->string('hospital')->nullable();

            $table->boolean('verified')->default(false);
            $table->date('verified_date')->nullable();
            $table->string('verified_using')->nullable()->comment('e.g., ID card, passport');
            $table->string('verification_code')->nullable();
            $table->string('verification_document')->nullable();


            $table->string('specialization')->nullable();
            $table->string('license_number')->nullable();
            $table->date('license_issue_date')->nullable();
            $table->date('license_expiry_date')->nullable();

            $table->unsignedInteger('years_of_experience')->nullable();
            $table->string('medical_school')->nullable();
            $table->date('graduation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
