<?php

namespace App\Models;

use Database\Factories\DisciplinaryRecordFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisciplinaryRecord extends Model
{
    /** @use HasFactory<DisciplinaryRecordFactory> */
    use HasFactory;
    use HasUlids;

    protected $table = 'disciplinary_records';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'policeman_id',
        'infraction',
        'penalty',
        'organ',
        'process_number',
        'record_date',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

    /**
     * Get the policeman associated with this disciplinary record.
     */
    public function policeman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'policeman_id');
    }
}
