<?php

namespace App\Models;

use Database\Factories\ReintegrationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reintegration extends Model
{
    /** @use HasFactory<ReintegrationFactory> */
    use HasFactory;

    protected $table = 'reintegrations';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'policeman_id',
        'departure_type',
        'reason',
        'unit',
        'category',
        'dispatch_report',
        'reintegration_date',
    ];


    protected function casts(): array
    {
        return [
            'reintegration_date' => 'date:Y-m-d',
        ];
    }


    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'policeman_id');
    }
}
