<?php

namespace App\Models;

use Database\Factories\InvoiceRowFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceRow extends Model
{
    /** @use HasFactory<InvoiceRowFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'invoice_code',
        'description',
        'due_date',
        'quantity',
        'price',
        'discount',
        'tax',
        'subtotal',
        'total',
        'paid',
        'remaining',
        'due',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date:Y-m-d',
            'quantity' => 'integer',
            'price' => 'string',
            'discount' => 'string',
            'tax' => 'string',
            'subtotal' => 'string',
            'total' => 'string',
            'paid' => 'string',
            'remaining' => 'string',
            'due' => 'string'
        ];
    }


    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_code', 'code');
    }
}
