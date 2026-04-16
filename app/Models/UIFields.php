<?php

namespace App\Models;

use App\Data\ColumnOrderDto;
use App\Enum\FormUIMap;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;

class UIFields extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    # this table has not id

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'model_type',
        'fields',
        'selected_fields',
        'hidden_fields',
        'columns_order'
    ];

    protected function casts(): array
    {
        return [
            'selected_fields' => 'array',
            'fields' => 'array',
            'hidden_fields' => 'array',
            'model_type' => FormUIMap::class,
            'columns_order' => DataCollection::class . ':'. ColumnOrderDto::class,
        ];
    }
}
