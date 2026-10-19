<?php

namespace App\Data;

use App\Models\Invoice;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;


class InvoiceData extends Data
{
     public function __construct(
         public string $id,
         #[Date, Nullable]
         #[WithCast(DateTimeInterfaceCast::class)]
         #[WithTransformer(DateTimeInterfaceTransformer::class)]
         public Carbon|string|null $date = null,
         #[Date, Nullable]
         #[WithCast(DateTimeInterfaceCast::class)]
         #[WithTransformer(DateTimeInterfaceTransformer::class)]
         public Carbon|string|null $due_date = null,
         #[WithCast(DateTimeInterfaceCast::class)]
         #[WithTransformer(DateTimeInterfaceTransformer::class)]
         public Carbon|string|null $paid_date = null,
         #[WithCast(DateTimeInterfaceCast::class)]
         #[WithTransformer(DateTimeInterfaceTransformer::class)]
         public Carbon|string|null $cancelled_date = null,
         public ?string $code = null,
         public ?string $reference = null,
         public ?string $notes = null,
         public ?string $status = null,
         public ?string $patient_name = null,
         public ?string $case_title = null,
         public ?string $discount = '0.00',
         public ?string $tax = '0.00',
         public ?string $subtotal = '0.00',
         public ?string $total = '0.00',
         public ?string $paid = '0.00',
         public ?string $remaining = '0.00',
         public ?string $due = '0.00',
     ){}


    public static  function fromModel(Invoice $invoice): self
    {
        $invoice->loadMissing(['user', 'patientCase', 'rows']);
        return new self(
            id: (string) $invoice->id,
            date: $invoice->issue_date,
            due_date: $invoice->due_date,
            paid_date: $invoice->paid_date,
            cancelled_date: $invoice->cancelled_date,
            code: $invoice->code,
            reference: $invoice->reference,
            notes: $invoice->notes,
            status: $invoice->status->value,
            patient_name: $invoice->user?->name,
            case_title: $invoice->patientCase?->title,
            discount: $invoice->rows->sum('discount'),
            tax: $invoice->rows->sum('tax'),
            subtotal: $invoice->rows->sum('subtotal'),
            total: $invoice->rows->sum('total'),
            paid: $invoice->rows->sum('paid'),
            remaining: $invoice->rows->sum('remaining'),
            due: $invoice->rows->sum('due')
        );
    }
}
