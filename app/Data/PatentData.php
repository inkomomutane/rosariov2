<?php

namespace App\Data;

use App\Models\Patent;
use Spatie\LaravelData\Data;

/** @typescript  */
class PatentData extends Data
{
    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $description = null,
    ) {
    }

    public static function fromModel(Patent  $model): self
    {
        return new self(
            $model->id,
            $model->title,
            $model->description
        );
    }
}
