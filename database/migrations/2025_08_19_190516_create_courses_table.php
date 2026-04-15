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
        Schema::create('courses',static  function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('slug')->unique();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->decimal('progress', 5, 2)->default(0.00);
            $table->boolean('is_active')->nullable()->default(true);
            $table->boolean('is_public')->nullable()->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
