<?php

namespace App\Models;

use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    /** @use HasFactory<AddressFactory> */
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = [
        'policeman_id',
        'province',
        'district',
        'locality',
        'neighborhood',
        'block',
        'cell',
        'street_or_nearby',
        'city',
        'circle',
        'phone_number',
        'email',
    ];

    /**
     * Get the policeman that owns the address.
     */
    public function policeman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'policeman_id', 'id');
    }
}
