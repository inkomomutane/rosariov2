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
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'user_id'
    ];

    protected function casts(): array
    {
        return [
            'gender' => Sex::class,
            'birth_date' => 'date:Y-m-d',
            'death_date' => 'date:Y-m-d',
            'marital_status' => MaritalStatus::class
        ];
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }


    public function cases(): HasManyThrough {
        return $this->hasManyThrough(PatientCase::class, Patient::class, 'person_id', 'patient_id');
    }
}
