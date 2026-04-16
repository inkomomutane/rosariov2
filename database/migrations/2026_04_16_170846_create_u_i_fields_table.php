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
        Schema::create('u_i_fields',static  function (Blueprint $table) {
             $table->string('user_id');
             $table->string('model_type');
             $table->jsonb('fields')->default(json_encode([], JSON_THROW_ON_ERROR))->nullable();
             $table->jsonb('selected_fields')->default(json_encode([], JSON_THROW_ON_ERROR))->nullable();
             $table->jsonb('hidden_fields')->default(json_encode([], JSON_THROW_ON_ERROR))->nullable();
             $table->jsonb('columns_order')->default(json_encode([], JSON_THROW_ON_ERROR))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('u_i_fields');
    }
};
