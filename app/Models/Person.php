<?php

namespace App\Models;

use App\Enum\MaritalStatus;
use App\Enum\Sex;
use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    /** @use HasFactory<PersonFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'name',
        'middle_name',
        'last_name',
        'birth_date',
        'gender',
        'marital_status',
        'nationality',
        'province',
        'city',
        'postal_code',
        'address_one',
        'address_two',
        'address_three',
        'address_four',
        'phone_one',
        'phone_two',
        'phone_three',
        'phone_four',
        'death_date',
        'cause_of_death',
        'created_by_name',
        'created_by_id',
        'user_id',
        'registration_date',
        'is_doctor',
        'is_patient',
        'verified',
        'verified_date',
        'verified_using',
        'verification_code',
        'verification_document',
        'hospital',
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
            'gender' => Sex::class,
            'birth_date' => 'date:Y-m-d',
            'death_date' => 'date:Y-m-d',
            'marital_status' => MaritalStatus::class,
            'verified_date' => 'date:Y-m-d',
            'license_issue_date' => 'date:Y-m-d',
            'license_expiry_date' => 'date:Y-m-d',
            'graduation_date' => 'date:Y-m-d',
            'registration_date' => 'date:Y-m-d',
            'is_doctor' => 'boolean',
            'is_patient' => 'boolean',
        ];
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cases(): HasMany
    {
        return $this->hasMany(PatientCase::class, 'person_id');
    }
}
