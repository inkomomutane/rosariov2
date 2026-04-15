<?php

namespace App\Http\Controllers\MemberInfo\Experience;

use App\Data\ExperienceDto;
use App\Enum\EmploymentType;
use App\Models\Experience;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class ListMemberExperience
{
    public function __invoke(User $member): Response
    {
        return Inertia::render('MemberInfo/Experience/ListExperience', [
            'experiences' => static::handle($member->id, request()->search),
            'employment_types' => EmploymentType::toValues(),
            'member' => $member,
        ]);
    }

    public static function handle(string $memberId, ?string $term = ''): mixed
    {
        return ExperienceDto::collect(
            Experience::query()->whereUserId($memberId)->when($term, function ($query, $search) {
                $query->whereAny([
                    'title',
                    'employment_type',
                    'company',
                    'start_date',
                    'end_date',
                    'location',
                    'description',
                ], 'ilike', '%'.$search.'%');
            })
            ->orderBy('start_date', 'desc')
            ->paginate(5)
            ->withQueryString()
        );
    }
}
