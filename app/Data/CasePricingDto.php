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

     public static function fromModel(\App\Models\CasePricing $casePricing): self {
         return new self(
             id: $casePricing->id,case_type: $casePricing->case_type->value, price: $casePricing->price, review_phase: $casePricing->review_phase
         );
     }
}
