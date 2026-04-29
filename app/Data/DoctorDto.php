<?php

namespace App\Data;


use App\Models\Person;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * @typescript
 */
class DoctorDto extends Data
{
    public function __construct(
        public string $name,
        public string $last_name,
        public ?string $hospital,
        public ?string $specialization,
        public ?string $license_number,

        #[Date, Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $license_issue_date,

        #[Date, Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $license_expiry_date,

        public ?int $years_of_experience,
        public ?string $medical_school,

        #[Date, Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $graduation_date,
    ) {}

    public static function fromModel(Person $person): self
    {
        return new self(
            name: $person->name,
            last_name: $person->last_name,
            hospital: $person->hospital,
            specialization: $person->specialization,
            license_number: $person->license_number,
            license_issue_date: $person->license_issue_date,
            license_expiry_date: $person->license_expiry_date,
            years_of_experience: $person->years_of_experience,
            medical_school: $person->medical_school,
            graduation_date: $person->graduation_date,
        );
    }
}
