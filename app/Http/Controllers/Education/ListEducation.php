<?php

namespace App\Http\Controllers\Education;

use App\Data\EducationDto;
use App\Models\Education;
use Inertia\Inertia;
use Inertia\Response;

class ListEducation
{
    public function __invoke(): Response
    {
        return Inertia::render('Education/ListModule', [
            'educations' => static::handle(request()->search),
        ]);
    }

    public static function handle(?string $term = ''): mixed
    {
        return EducationDto::collect(
            Education::query()->whereUserId(auth()->user()->id)->when($term, function ($query, $search) {
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
