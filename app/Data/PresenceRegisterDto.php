<?php

namespace App\Data;

use App\Models\PresenceRegister;
use Spatie\LaravelData\Data;

/** @typescript */
class PresenceRegisterDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $user_id = null,
        public ?string $user_name = null,
        public ?string $date,
        public ?string $check_in_time,
        public ?string $check_out_time,
        public ?string $check_in_location,
        public ?string $check_out_location,
        public ?string $notes,
        public ?bool   $is_late,
        public ?bool   $is_early_departure,
        public ?int    $total_hours_minutes,
    ) {}

    public static function fromModel(PresenceRegister $presenceRegister): self
    {

        $presenceRegister->loadMissing('user');

        return new self(
            $presenceRegister->id,
            $presenceRegister->user_id,
            $presenceRegister->user?->name,
            $presenceRegister->date?->format('Y-m-d'),
            $presenceRegister->check_in_time?->format('H:i'),
            $presenceRegister->check_out_time?->format('H:i'),
            $presenceRegister->check_in_location,
            $presenceRegister->check_out_location,
            $presenceRegister->notes,
            $presenceRegister->is_late,
            $presenceRegister->is_early_departure,
            $presenceRegister->total_hours_minutes,
        );
    }

    public function toArray(): array
    {
        return [
            'date' => $this->date,

            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'check_in_location' => $this->check_in_location,
            'check_out_location' => $this->check_out_location,
            'notes' => $this->notes,
            'is_late' => $this->is_late,
            'is_early_departure' => $this->is_early_departure,
            'total_hours_minutes' => $this->total_hours_minutes,
        ];
    }
}
