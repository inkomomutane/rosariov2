<?php

namespace App\Data;

use Spatie\LaravelData\Dto;

/** @typescript  */
class KeyValueDto extends Dto
{
    public function __construct(
        public string $key,
        public string $value,
    ) {}

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}
