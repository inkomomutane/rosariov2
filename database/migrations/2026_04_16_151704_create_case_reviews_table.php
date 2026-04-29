<?php

use App\Enum\ReviewStatus;
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
        Schema::create('case_reviews', static function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->string('case_id');

            $table->string('reviewer_doctor_id');
            $table->text('review_notes')->nullable();
            $table->date('review_date')->nullable();
            $table->text('review_conclusion')->nullable();

            $table->string('status')->default(ReviewStatus::PENDING);
            $table->timestamps();
            $table->foreign('case_id')->references('id')->on('patient_cases')->cascadeOnDelete();
            $table->foreign('reviewer_doctor_id')->references('id')->on('people')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_reviews');
    }
};
