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
        public string  $type = 'paciente',
        public ?bool   $verified = false,
        public ?string $address = '',
        public ?string $phone = '',
        public ?string $specialization,
        public ?int    $cases = 0,
        public ?string $balance = '0.00',
        public ?string $year_of_experience = '',

    )
    {
    }


    public static function fromModel(User $user): self
    {

        $user->loadMissing(['person', 'payments']);

        return new self(
            name: $user->name,
            email: $user->email,
            type: $user->person?->is_doctor ? 'doctor' : 'paciente',
            verified: (bool)$user->person?->verified,
            address: self::address($user),
            phone: self::phone($user),
            specialization: $user->person?->specialization,
            cases: $user->person?->cases->count() ?? 0,
            balance: $user->payments?->sum('debit') - $user->payments?->sum('credit')
        );
    }

    public static function address(User $user): string
    {
        $lines = [];
        $person = $user->person;
        if($person->address_one) {
            $lines[] = $person->address_one;
        }elseif($person->address_two) {
            $lines[] = $person->address_two;
        }elseif($person->address_three) {
            $lines[] = $person->address_three;
        }elseif ($person->address_four) {
            $lines[] = $person->address_four;
        }

        if($person->city) {
            $lines[] = $person->city;
        }

        if($person->province) {
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

        if($person->phone_four) {
            return $person->phone_four;
        }

        return '';
    }
}
