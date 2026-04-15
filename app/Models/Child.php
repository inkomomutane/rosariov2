<?php

namespace App\Models;

use Database\Factories\ChildFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Child extends Model
{
    /** @use HasFactory<ChildFactory> */
    use HasFactory;

    protected $table = 'children';

    protected $fillable = [
        'policeman_id',
        'child_name',
        'child_gender',
        'child_date_of_birth',
        'child_occupation',
    ];

    protected $casts = [
        'child_date_of_birth' => 'date:Y-m-d',
    ];

    /**
     * Get the policeman that owns the child.
     */
    public function policeman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'policeman_id', 'id');
    }
}
