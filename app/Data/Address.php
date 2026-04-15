<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/** @typescript */
final class Address extends Data
{
    public function __construct(
        public string $province,
        public string $city,
        public string $address,
        public string $zip_code,
        public string $country,
    ){}

    public function toArray(): array
    {
        return [
            'province' => $this->province,
            'city' => $this->city,
            'address' => $this->address,
            'zip_code' => $this->zip_code,
            'country' => $this->country,
        ];
    }

    public static function fromArray(array $attributes): Address
    {
        return new self(
            province: $attributes['province'],
            city: $attributes['city'],
            address: $attributes['address'],
            zip_code: $attributes['zip_code'],
            country: $attributes['country'],
        );
    }
}
