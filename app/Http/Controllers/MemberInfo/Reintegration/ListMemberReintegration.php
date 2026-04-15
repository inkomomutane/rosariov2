<?php

namespace App\Http\Controllers\MemberInfo\Reintegration;

use App\Data\ReintegrationDto;
use App\Models\Reintegration;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class ListMemberReintegration
{
    public function __invoke(User $member): Response
    {
        return Inertia::render('MemberInfo/Reintegration/ListModule', [
            'reintegrations' => static::handle($member->id, request()->search),
            'member' => $member,
        ]);
    }

    public static function handle(string $memberId, ?string $term = ''): mixed
    {
        return ReintegrationDto::collect(
            Reintegration::query()->wherePolicemanId($memberId)->when($term, function ($query, $search) {
                $query->whereAny([
                    'program_name',
                    'organization',
                    'start_date',
                    'end_date',
                    'description',
                    'outcome',
                ], 'ilike', '%'.$search.'%');
            })
            ->orderBy('start_date', 'desc')
            ->paginate(5)
            ->withQueryString()
        );
    }
}
