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
        Schema::create('payments', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('user_id');
            $table->string('invoice_code')->nullable();
            $table->decimal('debit',18,6)->default(0)->nullable();
            $table->decimal('credit',18,6)->default(0)->nullable();
            $table->text('reference')->nullable();
            $table->text('description')->nullable();
            $table->date('document_date');
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->string('transaction_code')->nullable();
            $table->date('posted_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
