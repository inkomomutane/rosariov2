<?php

namespace App\Models;

use Database\Factories\DoctorFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    /** @use HasFactory<DoctorFactory> */
    use HasFactory;
    use HasUlids;


    protected $fillable = [
        'person_id',
        'registration_date',
        'hospital',
        'verified',
        'verified_date',
        'verified_using',
        'verification_code',
        'verification_document',
        'specialization',
        'license_number',
        'license_issue_date',
        'license_expiry_date',
        'years_of_experience',
        'medical_school',
        'graduation_date',
    ];


    protected function casts(): array
    {
        return [
            'registration_date' => 'date:Y-m-d',
            'verified_date' => 'date:Y-m-d',
            'license_issue_date' => 'date:Y-m-d',
            'license_expiry_date' => 'date:Y-m-d',
            'graduation_date' => 'date:Y-m-d',
        ];
    }


    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
