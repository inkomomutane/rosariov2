<?php

namespace App\Models;

use App\Enum\InvoiceStatus;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'code',
        'issue_date',
        'due_date',
        'paid_date',
        'cancelled_date',
        'user_id',
        'status',
        'reference',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date:Y-m-d',
            'due_date' => 'date:Y-m-d',
            'paid_date' => 'date:Y-m-d',
            'cancelled_date' => 'date:Y-m-d',
            'status' => InvoiceStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function rows (): HasMany
    {
        return $this->hasMany(InvoiceRow::class, 'invoice_code', 'code');
    }

}

