<?php

namespace App\Models;

use App\Contracts\WithFilesInterface;
use App\Traits\WithFiles;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Payment extends Model implements  WithFilesInterface,HasMedia
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;
    use HasUlids;
    use InteractsWithMedia;
    use WithFiles;


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
