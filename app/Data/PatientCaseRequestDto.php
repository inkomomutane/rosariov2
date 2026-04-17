<?php

namespace App\Data;

use App\Enum\CaseType;
use App\Enum\PatientCaseStatus;
use App\Enum\Urgency;
use App\Models\PatientCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

/**
 * @typescript
 */
class PatientCaseRequestDto extends Data
{
    public function __construct(
        public Urgency $priority,
        public string  $title,
        public string  $description,
        public ?PatientCaseStatus  $status = PatientCaseStatus::SUBMITTED,
        public ?CaseType $case_type = null,
        /** @var UploadedFile[]|array<UploadedFile> $attachments   */
        public ?array  $attachments = [],
        public null|array|Collection $uploaded_attachments = null,
        public ?string $id = null,
        public ?string $case_code = null,
    ){}

    public  static  function fromModel(PatientCase $case): self
    {
        return new self(
            priority: $case->priority,
            title: $case->title,
            description: $case->description,
            status: $case->status,
            case_type: $case->case_type,
            uploaded_attachments: $case->uploaded_attachments,id: $case->id, case_code: $case->case_code
        );
    }
}
