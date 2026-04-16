<?php

namespace App\Models;

use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'invoice_code',
        'debit',
        'credit',
        'reference',
        'description',
        'document_date',
        'due_date',
        'payment_date',
        'transaction_code',
        'posted_date',
    ];

    protected function casts(): array
    {
        return [
            'document_date' => 'date:Y-m-d',
            'due_date' => 'date:Y-m-d',
            'payment_date' => 'date:Y-m-d',
            'posted_date' => 'date:Y-m-d',
        ];
    }
}
