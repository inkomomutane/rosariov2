<?php

namespace App\Http\Controllers\MemberInfo\DisciplinaryRecords;

use App\Data\DisciplinaryRecordDto;
use App\Models\DisciplinaryRecord;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class ListMemberDisciplinaryRecords
{
    public function __invoke(User $member): Response
    {
        return Inertia::render('MemberInfo/DisciplinaryRecord/MemberDisciplinaryRecord', [
            'disciplinaryRecords' => static::handle($member->id, request()->search),
            'member' => $member,
        ]);
    }

    public static function handle(string $memberId, ?string $term = ''): mixed
    {
        return DisciplinaryRecordDto::collect(
            DisciplinaryRecord::query()->where('policeman_id', $memberId)->when($term, function ($query, $search) {
                $query->whereAny([
                    'infraction',
                    'penalty',
                    'organ',
                    'process_number',
                ], 'ilike', '%'.$search.'%');
            })
            ->orderBy('record_date', 'desc')
            ->paginate(5)
            ->withQueryString()
        );
    }
}
