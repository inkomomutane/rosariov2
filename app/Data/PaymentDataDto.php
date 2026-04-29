<?php

namespace App\Data;

use App\Enum\PaymentType;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * @typescript
 */
class PaymentDataDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $user_name,
        public ?string $invoice_code,
        public ?string $reference,
        public ?string $description,
        public ?string $transaction_code,
        public ?string $debit = null,
        public ?string $credit = null,
        #[Date, Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $document_date = null,
        #[Date, Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $due_date = null,
        #[Date, Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $payment_date = null,
        #[Date, Nullable]
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|string|null $posted_date = null,
        public ?PaymentType $type = null,
    ) {}


    public static function fromModel(Payment $payment): self
    {
        $payment->loadMissing('user');
        return new self(
            id: $payment->id,
            user_name: $payment->user?->name,
            invoice_code: $payment->invoice_code,
            reference: $payment->reference,
            description: $payment->description,
            transaction_code: $payment->transaction_code,
            debit: $payment->debit,
            credit: $payment->credit,
            document_date: $payment->document_date,
            due_date: $payment->due_date,
            payment_date: $payment->payment_date,
            posted_date: $payment->posted_date,
            type: $payment->type
        );
    }
}
