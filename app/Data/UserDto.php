<?php

namespace App\Data;

use App\Enum\MaritalStatus;
use App\Enum\Sex;
use App\Models\User;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/** @typescript  */
class UserDto extends Data
{
    public function __construct(
        public string             $name,
        #[Required,Email]
        public string             $email,
        #[Unique(table: 'users', column: 'NIP')]
        public ?string            $NIP = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon|string|null $email_verified_at = null,
        public ?string            $remember_token = null,
        public ?string            $first_name,
        public ?string            $second_name,
        public ?string            $last_name = null,
        public ?Sex               $sex = null,
        public ?string            $language = null,
        public ?array             $other_languages = [],
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon|string      $birth_date,
        public ?string            $place_of_birth = null,
        public ?string            $nationality = null,
        public ?MaritalStatus     $marital_status = null,
        public ?string            $id_number = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon|string|null $id_emitted_at = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon|string|null $id_expires_at = null,
        public ?string            $passport_number = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon|string|null $passport_emitted_at = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon|string|null $passport_expires_at = null,
        public ?string            $tax_number = null,
        public ?string            $driver_licence = null,
        public ?string             $nip =null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon|string|null $driver_licence_emitted_at = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon|string|null $driver_licence_expires_at = null,
        public ?string            $dir = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon|string|null $dir_emitted_at = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon|string|null $dir_expires_at = null,

        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon|string $ingress_date,

        public ?array             $contacts = [],
        public ?string            $primary_contact = null,
        public ?array             $emails = [],
        public ?string            $primary_email = null,
        public ?string            $id = null,

        public ?string            $patent_id = null,
        public ?string            $patent_name = '',
        public ?bool              $is_admin = null,
        public ?string            $password = '',
        public ?string            $phone = '',
        public ?string             $avatar = ''

    )
    {
    }


    public static function fromModel(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            NIP: $user->nip,
            email_verified_at: $user->email_verified_at,
            remember_token: $user->remember_token,
            first_name: $user->first_name,
            second_name: $user->second_name,
            last_name: $user->last_name,
            sex: $user->sex,
            language: $user->language,
            other_languages: $user->other_languages,
            birth_date: $user->birth_date,
            place_of_birth: $user->place_of_birth,
            nationality: $user->nationality,
            marital_status: $user->marital_status,
            id_number: $user->id_number,
            id_emitted_at: $user->id_emitted_at,
            id_expires_at: $user->id_expires_at,
            passport_number: $user->passport_number,
            passport_emitted_at: $user->passport_emitted_at,
            passport_expires_at: $user->passport_expires_at,
            tax_number: $user->tax_number,
            driver_licence: $user->driver_licence,
            nip: $user->nip,
            driver_licence_emitted_at: $user->driver_licence_emitted_at,
            driver_licence_expires_at: $user->driver_licence_expires_at,
            dir: $user->dir,
            dir_emitted_at: $user->dir_emitted_at,
            dir_expires_at: $user->dir_expires_at,
            ingress_date: $user->ingress_date,
            contacts: $user->contacts,
            primary_contact: $user->primary_contact,
            emails: $user->emails,
            primary_email: $user->primary_email,
            id: $user->id,
            patent_id: $user->patent_id,
            patent_name: $user->patent?->title,
            is_admin: $user->is_admin,
            password: '',
            phone: $user->phone,
            avatar: $user->getFirstMediaUrl('avatar')
        );
    }

}
