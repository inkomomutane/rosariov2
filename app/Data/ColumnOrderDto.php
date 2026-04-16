<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/** @typescript */
class ColumnOrderDto extends Data
{
    public function __construct(
        public int    $order,
        public string $field_name,
        public bool   $showing = true,
    )
    {
    }
}
