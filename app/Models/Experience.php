<?php

namespace App\Models;

use App\Data\ExperienceDto;
use App\Enum\EmploymentType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\LaravelData\WithData;

class Experience extends Model
{
    use HasUlids;
    use WithData;

    protected $fillable = [
        'title',
        'employment_type',
        'company',
        'start_date',
        'end_date',
        'location',
        'description',
        'user_id',
    ];


    public string $dataClass = ExperienceDto::class;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'employment_type' => EmploymentType::class,
        ];
    }
}
