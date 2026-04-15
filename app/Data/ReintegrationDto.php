<?php
namespace App\Data;

use App\Models\Reintegration;
use Spatie\LaravelData\Data;

/**
 * @typescript
 */
class ReintegrationDto extends Data
{
    public function __construct(
        public ?int $id,
        public ?int $policeman_id = null,
        public ?string $departure_type = null,
        public ?string $reason = null,
        public ?string $unit = null,
        public ?string $category = null,
        public ?string $dispatch_report = null,
        public ?string $reintegration_date = null,
    ) {}

    public static function fromModel(Reintegration $reintegration): self
    {
        return new self(
            $reintegration->id,
            $reintegration->policeman_id,
            $reintegration->departure_type,
            $reintegration->reason,
            $reintegration->unit,
            $reintegration->category,
            $reintegration->dispatch_report,
            $reintegration->reintegration_date
        );
    }
}

