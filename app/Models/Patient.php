<?php

namespace App\Models;

use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    /** @use HasFactory<PatientFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'person_id',
        'registration_date',
        'hospital',
        'verified',
        'verified_using',
        'verification_code',
        'verification_document',
        'verification_date',
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'date:Y-m-d',
            'verified_date' => 'date:Y-m-d',
        ];
    }


    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
