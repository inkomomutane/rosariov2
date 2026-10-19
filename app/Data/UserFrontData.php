<?php

namespace App\Data;

use App\Models\User;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class UserFrontData extends Data
{
    public function __construct(
        public string  $name,
        #[Required, Email]
        public string  $email,
        public ?string $specialization,
        public string  $type = 'paciente',
        public ?bool   $verified = false,
        public ?string $address = '',
        public ?string $phone = '',
        public ?int    $cases = 0,
        public ?string $balance = '0.00',
        public ?string $year_of_experience = '',
        public ?bool   $is_admin = false,

    )
    {
    }


    public static function fromModel(?User $user): ?self
    {
        if (!$user) {
            return null;
        }

        $user->loadMissing(['person', 'payments']);

        return new self(
            name: $user->person->name . ' ' . $user->person->last_name,
            email: $user->email,
            specialization: $user->person?->specialization,
            type: $user->person?->is_doctor ? 'doctor' : 'paciente',
            verified: (bool)$user->person?->verified,
            address: self::address($user),
            phone: self::phone($user),
            cases: $user->person?->cases->count() ?? 0,
            balance: $user->payments?->sum('debit') - $user->payments?->sum('credit'),
            year_of_experience: $user->person?->years_of_experience,
            is_admin: true,
        );
    }

    public static function address(User $user): string
    {
        $lines = [];
        $person = $user->person;
        if ($person->address_one) {
            $lines[] = $person->address_one;
        } elseif ($person->address_two) {
            $lines[] = $person->address_two;
        } elseif ($person->address_three) {
            $lines[] = $person->address_three;
        } elseif ($person->address_four) {
            $lines[] = $person->address_four;
        }

        if ($person->city) {
            $lines[] = $person->city;
        }

        if ($person->province) {
            $lines[] = $person->state;
        }

        return implode(', ', $lines);
    }

    public static function phone(User $user): string
    {
        $person = $user->person;
        if ($person->phone_one) {
            return $person->phone_one;
        }

        if ($person->phone_two) {
            return $person->phone_two;
        }

        if ($person->phone_three) {
            return $person->phone_three;
        }

        if ($person->phone_four) {
            return $person->phone_four;
        }

        return '';
    }
}
