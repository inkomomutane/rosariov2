<?php

namespace App\Data;

use App\Enum\CaseType;
use App\Enum\PatientCaseStatus;
use App\Enum\Urgency;
use App\Models\PatientCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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
        public ?string            $person_id,
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
        public ?string            $id = null,
        public Collection|array|null              $attachments = [],




    )
    {
    }

    public static function  fromModel(PatientCase $case): self
    {

        $case->loadMissing(['patient','requesterDoctor','assignedDoctor','lastReviewerDoctor']);

        return new self(
            case_code: $case->case_code,
            person_id: $case->person_id,
            patient_name: $case->patient?->name,
            patient_last_name: $case->patient?->last_name,
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
            requester_doctor_name: $case->requesterDoctor?->name . ' ' . $case->requesterDoctor?->last_name,
            status: $case->status,
            assigned_doctor_name: $case->assignedDoctor?->name . ' ' . $case->assignedDoctor?->last_name,
            last_reviewer_doctor_name: $case->lastReviewerDoctor?->name . ' ' . $case->lastReviewerDoctor?->last_name,
            id: $case->id,
            attachments:  $case->uploaded_attachments
        );
    }
}
