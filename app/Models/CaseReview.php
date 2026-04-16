<?php

namespace App\Models;

use App\Enum\ReviewStatus;
use Database\Factories\CaseReviewFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseReview extends Model
{
    /** @use HasFactory<CaseReviewFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'case_id',
        'reviewer_doctor_id',
        'review_notes',
        'review_date',
        'review_conclusion',
        'status',
    ];


    protected function casts(): array
    {
        return [
            'review_date' => 'date:Y-m-d',
            'status' => ReviewStatus::class,
        ];
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(PatientCase::class);
    }


    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
