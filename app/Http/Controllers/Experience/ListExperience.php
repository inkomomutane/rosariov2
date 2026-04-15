<?php

namespace App\Http\Controllers\Experience;

use App\Data\ExperienceDto;
use App\Enum\EmploymentType;
use App\Models\Experience;
use Inertia\Inertia;
use Inertia\Response;

class ListExperience
{
    public function __invoke(): Response
    {
        return Inertia::render('Experience/ListExperience', [
            'experiences' => static::handle(request()->search),
            'employment_types' => EmploymentType::toValues(),
        ]);
    }

    public static function handle(?string $term = ''): mixed
    {
        return ExperienceDto::collect(
            Experience::query()->whereUserId(auth()->user()->id)->when($term, function ($query, $search) {
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
