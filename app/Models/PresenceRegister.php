<?php

namespace App\Models;

use App\Data\PresenceRegisterDto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\LaravelData\WithData;

class PresenceRegister extends Model
{
    use HasUlids;
    use WithData;

    protected $fillable = [
        'user_id',
        'date',
        'check_in_time',
        'check_out_time',
        'check_in_location',
        'check_out_location',
        'notes',
        'is_late',
        'is_early_departure',
        'total_hours_minutes',
    ];

    public string $dataClass = PresenceRegisterDto::class;

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'check_in_time' => 'datetime:H:i',
            'check_out_time' => 'datetime:H:i',
            'is_late' => 'boolean',
            'is_early_departure' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper method to calculate total working hours
    public function calculateTotalHours(): ?int
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return null;
        }

        $checkIn = Carbon::parse($this->check_in_time);
        return Carbon::parse($this->check_out_time)->diffInMinutes($checkIn);
    }

    // Helper method to format total hours for display
    public function getTotalHoursFormatted(): string
    {
        if (!$this->total_hours_minutes) {
            return '0h 0m';
        }

        $hours = intval($this->total_hours_minutes / 60);
        $minutes = $this->total_hours_minutes % 60;

        return "{$hours}h {$minutes}m";
    }
}
