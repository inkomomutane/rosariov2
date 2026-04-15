<?php

namespace App\Data;

use App\Enum\EmploymentType;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

/** @typescript  */
class ExperienceDto extends Data
{
    public function __construct(
        public string $title,
        public EmploymentType $employment_type,
        public string $company,
        public Carbon $start_date,
        public ?Carbon $end_date = null,
        public ?string $location = null,
        public ?string $description = null,
        public ?string $id = null,
    ){}
}
