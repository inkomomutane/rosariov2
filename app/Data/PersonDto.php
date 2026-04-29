<?php

namespace App\Data;

use App\Enum\MaritalStatus;
use App\Enum\Sex;
use App\Models\Person;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * @typescript
 */
class PersonDto extends Data
{
    public function __construct(
        public string  $name,
        public string  $last_name,
        #[WithCast(EnumCast::class,type: Sex::class)]
        public ?Sex $gender,
        #[WithCast(EnumCast::class,type: MaritalStatus::class)]
        public ?MaritalStatus $marital_status,
        public ?string $middle_name,
        #[Date,Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $birth_date,
        public ?string $nationality,
        public ?string $province,
        public ?string $city,
        public ?string $postal_code,
        public ?string $address_one,
        public ?string $address_two,
        public ?string $address_three,
        public ?string $address_four,
        public ?string $phone_one,
        public ?string $phone_two,
        public ?string $phone_three,
        public ?string $phone_four,
        #[Date,Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $death_date,
        public ?string $cause_of_death,
    )
    {
    }


    public static function fromModel(Person $person): self
    {
        return new self(
            name: $person->name,
            last_name: $person->last_name,
            gender: $person->gender,
            marital_status: $person->marital_status,
            middle_name: $person->middle_name,
            birth_date: $person->birth_date,
            nationality: $person->nationality,
            province: $person->province,
            city: $person->city,
            postal_code: $person->postal_code,
            address_one: $person->address_one,
            address_two: $person->address_two,
            address_three: $person->address_three,
            address_four: $person->address_four,
            phone_one: $person->phone_one,
            phone_two: $person->phone_two,
            phone_three: $person->phone_three,
            phone_four: $person->phone_four,
            death_date: $person->death_date,
            cause_of_death: $person->cause_of_death
        );
    }
}
