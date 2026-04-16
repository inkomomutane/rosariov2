<?php

namespace App\Models;

use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /** @use HasFactory<PersonFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'name',
        'middle_name',
        'last_name',
        'birth_date',
        'gender',
        'marital_status',
        'nationality',
        'province',
        'city',
        'postal_code',
        'address_one',
        'address_two',
        'address_three',
        'address_four',
        'phone_one',
        'phone_two',
        'phone_three',
        'phone_four',
        'death_date',
        'cause_of_death',
        'created_by_name',
        'created_by_id',
    ];
}
