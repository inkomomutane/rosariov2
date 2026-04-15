<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasUlids;


    public $incrementing = false;

    protected $fillable = [
       'title',
       'description',
       'slug',
       'start_date',
       'end_date',
       'progress',
       'is_active',
       'is_public',
    ];


    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'progress' => 'float',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

}
