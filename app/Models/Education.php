<?php

namespace App\Models;

use App\Data\EducationDto;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\LaravelData\WithData;

class Education extends Model
{
    use HasUlids;
    use WithData;

    protected $fillable = [
        'school',
        'degree',
        'field_of_study',
        'start_date',
        'end_date',
        'grade',
        'activities',
        'description',
        'user_id',
    ];



    public string $dataClass = EducationDto::class;

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
