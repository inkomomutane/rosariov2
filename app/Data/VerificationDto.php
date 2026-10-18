<?php

namespace App\Data;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class VerificationDto extends Data
{
    public function __construct(
        public bool $is_verified,
        #[Date,Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $verified_at,
        public string $verified_using,
        public ?string $verification_code,
        public string  $verification_document,
        /** @var UploadedFile[]|array<UploadedFile> $attachments   */
        public ?array  $attachments = [],
        public null|array|Collection $uploaded_attachments = null,
    ) {}
}
