<?php

namespace App\Http\Controllers\DisciplinaryRecord;

use App\Data\DisciplinaryRecordDto;
use App\Models\DisciplinaryRecord;
use App\Models\User;

class ListDisciplinaryRecord
{
    public static function handle(User $user,?string $term = ''): mixed
    {
        return DisciplinaryRecordDto::collect(
            DisciplinaryRecord::query()->where('policeman_id', $user->id)->when($term, function ($query, $search) {
                $query->whereAny([
                    'infraction',
                    'penalty',
                    'organ',
                    'process_number',
                    'record_date',
                ], 'ilike', '%'.$search.'%');
            })
            ->orderBy('record_date', 'desc')
            ->paginate(5)
            ->withQueryString()
        );
    }
}
