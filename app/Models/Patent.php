<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class Patent extends Model
{
    use HasUlids;
    use WithData;



    public string $dataClass = \App\Data\PatentData::class;
    public $incrementing = false;

    protected $fillable = [
        'title',
        'description'
    ];

}
