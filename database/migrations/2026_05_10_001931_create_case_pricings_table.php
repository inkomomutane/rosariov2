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
        Schema::create('case_pricing', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('case_type');
            $table->decimal('price',18,6)->default(0);
            $table->unsignedInteger('review_phase')->default(1);
            $table->timestamps();

            $table->unique(['case_type','review_phase']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_pricing');
    }
};
