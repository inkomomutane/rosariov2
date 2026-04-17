<?php

namespace App\Models;

use App\Contracts\WithFilesInterface;
use App\Enum\CaseType;
use App\Enum\PatientCaseStatus;
use App\Enum\Urgency;
use App\Traits\WithFiles;
use Database\Factories\PatientCaseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PatientCase extends Model implements HasMedia,WithFilesInterface
{
    /** @use HasFactory<PatientCaseFactory> */
    use HasFactory;
    use HasUlids;
    use InteractsWithMedia;
    use WithFiles;

    protected $fillable = [
        'case_code',
        'patient_id',
        'priority',
        'title',
        'description',
        'case_type',
        'first_review',
        'final_review',
        'case_date',
        'first_review_date',
        'last_review_date',
        'requester_doctor_id',
        'status',
        'assigned_doctor_id',
        'last_reviewer_doctor_id',
    ];


    protected function casts(): array
    {
        return [
            'case_date' => 'date:Y-m-d',
            'first_review_date' => 'date:Y-m-d',
            'last_review_date' => 'date:Y-m-d',
            'status' => PatientCaseStatus::class,
            'case_type' => CaseType::class,
            'priority' => Urgency::class,
        ];
    }


    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function requesterDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }


    public function assignedDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function lastReviewerDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

}
