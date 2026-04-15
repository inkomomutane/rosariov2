<?php

use Database\Seeders\PatentSeeder;
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
        Schema::create('patents', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Artisan::call('db:seed',[
            '--class' => PatentSeeder::class,
            '--force' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patents');
    }
};
