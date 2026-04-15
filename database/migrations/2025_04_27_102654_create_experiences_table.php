<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('experiences', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->string('employment_type');
            $table->string('company');
            $table->date('start_date');
            $table->string('end_date')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
