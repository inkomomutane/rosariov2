<?php

namespace App\Models;

use App\Contracts\WithFilesInterface;
use App\Enum\ReviewStatus;
use App\Traits\WithFiles;
use Database\Factories\CaseReviewFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CaseReview extends Model implements  WithFilesInterface,HasMedia
{
    /** @use HasFactory<CaseReviewFactory> */
    use HasFactory;
    use HasUlids;
    use InteractsWithMedia;
    use WithFiles;

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
        return $this->belongsTo(PatientCase::class, 'case_id', 'id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Person::class,'reviewer_doctor_id','id');
    }
}
