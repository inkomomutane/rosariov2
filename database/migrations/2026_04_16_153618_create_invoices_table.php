<?php

use App\Enum\InvoiceStatus;
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
        Schema::create('invoices', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code')->unique();

            $table->date('issue_date');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->date('cancelled_date')->nullable();


            $table->string('user_id');
            $table->string('status')->default(InvoiceStatus::ISSUED);

            $table->text('reference')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
