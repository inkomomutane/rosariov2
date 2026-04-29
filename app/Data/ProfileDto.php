<?php

namespace App\Data;

use App\Enum\MaritalStatus;
use App\Enum\Sex;
use App\Models\User;
use Spatie\LaravelData\Data;

/**
 * @typescript
 */
class ProfileDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?string $email,
        public ?string $email_verified_at,
        public string $full_name,
        public ?string $birth_date,
        public ?Sex $gender,
        public ?MaritalStatus $marital_status,
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
        public ?string $death_date,
        public ?string $cause_of_death,
        public ?string $user_id,
        public ?string $registration_date,
        public bool $is_doctor,
        public bool $is_patient,
        public bool $verified,
        public ?string $verified_date,
        public ?string $verified_using,
        public ?string $verification_code,
        public ?string $verification_document,
        public ?string $hospital,
        public ?string $specialization,
        public ?string $license_number,
        public ?string $license_issue_date,
        public ?string $license_expiry_date,
        public ?string $years_of_experience,
        public ?string $medical_school,
        public ?string $graduation_date,
    ) {}

    public static function fromModel(User $user): self
    {

        $user->loadMissing(['person']);

        return new self(
            id: (string) $user->id,
            name: $user->name,
            email: $user->email,
            email_verified_at: $user->email_verified_at?->toDateTimeString(),
            full_name: $user->person?->name . ' ' . $user->person?->last_name,
            birth_date: $user->person?->birth_date?->toDateString(),
            gender: $user->person?->gender,
            marital_status: $user->person?->marital_status,
            nationality: $user->person?->nationality,
            province: $user->person?->province,
            city: $user->person?->city,
            postal_code: $user->person?->postal_code,
            address_one: $user->person?->address_one,
            address_two: $user->person?->address_two,
            address_three: $user->person?->address_three,
            address_four: $user->person?->address_four,
            phone_one: $user->person?->phone_one,
            phone_two: $user->person?->phone_two,
            phone_three: $user->person?->phone_three,
            phone_four: $user->person?->phone_four,
            death_date: $user->person?->death_date?->toDateString(),
            cause_of_death: $user->person?->cause_of_death,
            user_id: (string) $user->id,
            registration_date: $user->created_at?->toDateTimeString(),
            is_doctor: $user->person?->is_doctor, // Assuming Spatie Permission or similar logic
            is_patient: $user->person?->is_patient, // Assuming Spatie Permission or similar logic
            verified: (bool) $user->person?->verified,
            verified_date: $user->person?->verified_date?->toDateTimeString(),
            verified_using: $user->person?->verified_using,
            verification_code: null, // Usually excluded for security in DTOs
            verification_document: $user->person?->verification_document,
            hospital: $user->person?->hospital,
            specialization: $user->person?->specialization,
            license_number: $user->person?->license_number,
            license_issue_date: $user->person?->license_issue_date?->toDateString(),
            license_expiry_date: $user->person?->license_expiry_date?->toDateString(),
            years_of_experience: (string) $user->person?->years_of_experience,
            medical_school: $user->person?->medical_school,
            graduation_date: $user->person?->graduation_date?->toDateString(),
        );
    }
}
