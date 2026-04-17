<?php

namespace App\Data;

use App\Enum\CaseType;
use App\Enum\PatientCaseStatus;
use App\Enum\Urgency;
use App\Models\PatientCase;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * @typescript
 */
class FullPatientCaseDto extends Data
{

    public function __construct(
        public ?string            $case_code,
        public ?string            $patient_id,
        public ?string            $patient_name,
        public ?string            $patient_last_name,
        public ?bool              $patient_verified,
        #[WithCast(EnumCast::class, type: Urgency::class)]
        public ?Urgency           $priority,
        public ?string            $title,
        public ?string            $description,
        #[WithCast(EnumCast::class, type: CaseType::class)]
        public ?CaseType          $case_type,
        public ?string            $first_review,
        public ?string            $final_review,
        #[Date, Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $case_date,
        #[Date, Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $first_review_date,
        #[Date, Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $last_review_date,
        public ?string            $requester_doctor_name,
        #[WithCast(EnumCast::class, type: PatientCaseStatus::class)]
        public ?PatientCaseStatus $status,
        public ?string            $assigned_doctor_name,
        public ?string            $last_reviewer_doctor_name,




    )
    {
    }

    public static function  fromModel(PatientCase $case): self
    {

        $case->loadMissing(['patient.person','requesterDoctor.person','assignedDoctor.person','lastReviewerDoctor.person']);

        return new self(
            case_code: $case->case_code,
            patient_id: $case->patient_id,
            patient_name: $case->patient?->person?->name,
            patient_last_name: $case->patient?->person?->last_name,
            patient_verified: $case->patient?->verified,
            priority: $case->priority,
            title: $case->title,
            description: $case->description,
            case_type: $case->case_type,
            first_review: $case->first_review,
            final_review: $case->final_review,
            case_date: $case->case_date,
            first_review_date: $case->first_review_date,
            last_review_date: $case->last_review_date,
            requester_doctor_name: $case->requesterDoctor?->person?->name . ' ' . $case->requesterDoctor?->person?->last_name,
            status: $case->status,
            assigned_doctor_name: $case->assignedDoctor?->person?->name . ' ' . $case->assignedDoctor?->person?->last_name,
            last_reviewer_doctor_name: $case->lastReviewerDoctor?->person?->name . ' ' . $case->lastReviewerDoctor?->person?->last_name,
        );
    }
}
