<?php

namespace App\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

/**
 * @typescript
 */
class EducationDto extends Data
{
    public function __construct(
        public string $school,
        public string $degree,
        public string  $field_of_study,
        public Carbon $start_date,
        public ?Carbon $end_date = null,
        public ?string $grade = null,
        public ?string $activities = null,
        public ?string $description = null,
        public ?string $id = null,
    ){}
}
