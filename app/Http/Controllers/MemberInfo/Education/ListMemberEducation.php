<?php

namespace App\Http\Controllers\MemberInfo\Education;

use App\Data\EducationDto;
use App\Models\Education;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class ListMemberEducation
{
    public function __invoke(User $member): Response
    {
        return Inertia::render('MemberInfo/Education/ListModule', [
            'educations' => static::handle($member->id, request()->search),
            'member' => $member,
        ]);
    }

    public static function handle(string $memberId, ?string $term = ''): mixed
    {
        return EducationDto::collect(
            Education::query()->whereUserId($memberId)->when($term, function ($query, $search) {
                $query->whereAny([
                    'school',
                    'degree',
                    'field_of_study',
                    'start_date',
                    'end_date',
                    'grade',
                    'activities',
                    'description',
                ], 'ilike', '%'.$search.'%');
            })
            ->orderBy('start_date', 'desc')
            ->paginate(5)
            ->withQueryString()
        );
    }
}
