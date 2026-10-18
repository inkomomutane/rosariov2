<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class CasePricing extends Model
{
    use HasUlids;

    protected $table = 'case_pricing';

    protected $fillable = [
        'case_type',
        'price',
        'review_phase',
    ];

}
