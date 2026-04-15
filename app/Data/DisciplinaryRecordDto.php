<?php

namespace App\Data;

use App\Models\DisciplinaryRecord;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Data;

/** @typescript */
class DisciplinaryRecordDto extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $infraction,
        #[Required, StringType]
        public string $penalty,
        #[Required, StringType]
        public string $organ,
        #[Required, StringType]
        public string $process_number,
        #[Required, Date]
        public Carbon $record_date,
        public ?string $id = null,
    ) {}

    public static function fromModel(DisciplinaryRecord $disciplinaryRecord): self
    {
        return new self(
            $disciplinaryRecord->infraction,
            $disciplinaryRecord->penalty,
            $disciplinaryRecord->organ,
            $disciplinaryRecord->process_number,
            $disciplinaryRecord->record_date,
            $disciplinaryRecord->id,
        );
    }
}
