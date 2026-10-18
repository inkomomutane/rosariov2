<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/** @typescript */
class CasePricingDto extends Data
{
    public function __construct(
        public string $id,
        public string $case_type,
        public string $price,
        public int $review_phase,
    )
    {
    }
}
