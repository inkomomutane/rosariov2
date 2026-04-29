<?php

use App\Enum\CaseType;
use App\Enum\PatientCaseStatus;
use App\Enum\Urgency;
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
        Schema::create('patient_cases', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('case_code');
            $table->string('person_id');
            $table->string('priority')->default(Urgency::NORMAL);
            $table->string('title',500)->nullable();
            $table->text('description')->nullable();
            $table->string('case_type')->nullable()->default(CaseType::OTHER)->comment('e.g., diagnosis, treatment, follow-up');
            $table->text('first_review')->nullable();
            $table->text('final_review')->nullable();
            $table->date('case_date')->nullable();
            $table->date('first_review_date')->nullable();
            $table->date('last_review_date')->nullable();
            $table->string('requester_doctor_id')->nullable();
            $table->string('assigned_doctor_id')->nullable();
            $table->string('last_reviewer_doctor_id')->nullable();

            $table->string('status')->default(PatientCaseStatus::SUBMITTED);
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('people')->cascadeOnDelete();
            $table->foreign('requester_doctor_id')->references('id')->on('people')->nullOnDelete();
            $table->foreign('assigned_doctor_id')->references('id')->on('people')->nullOnDelete();
            $table->foreign('last_reviewer_doctor_id')->references('id')->on('people')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_cases');
    }
};
