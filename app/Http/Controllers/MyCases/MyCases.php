<?php

namespace App\Http\Controllers\MyCases;

use App\Data\FullPatientCaseDto;
use App\Data\UserFrontData;
use App\Enum\CaseType;
use App\Enum\Urgency;
use App\Services\DoctorCaseServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MyCases
{
    public function __invoke(Request $request)
    {

        $user = $request->user();
        $person = $user->person;

        return Inertia::render('My/Cases/Index',[
            'cases' =>  Inertia::scroll(
                static fn () => FullPatientCaseDto::collect(
                    DoctorCaseServices::getAssignedCases($person, $request->input('search', ''))
                        ->paginate(15, pageName: 'review_cases_page')
                )
            ),
            'user' => UserFrontData::from($user),
            'person' => $person->only([
                'name',
                'last_name',
                'id',
                'gender',
                'marital_status',
            ]),
            'case_types' => CaseType::toValues(),
            'urgencies' => Urgency::toValues(),
        ]);
    }

    public function handle(?string $term = '',?int $per_page = 12) {
        return FullPatientCaseDto::collect( auth()->user()->person?->cases()
            ->with(['patient', 'requesterDoctor', 'assignedDoctor', 'lastReviewerDoctor'])
            ->when($term, function ($query, $term) {
                $query->whereAny([
                    'case_code',
                    'priority',
                    'title',
                    'description',
                    'case_type',
                    'first_review',
                    'final_review',
                    'status',
                ],'ilike', "%$term%")
                    ->orWhereHas('patient', function ($query) use ($term) {
                        $query->whereAny(['name', 'last_name',],'ilike', "%$term%");
                    })
                    ->orWhereHas('requesterDoctor', function ($query) use ($term) {
                        $query->whereAny(['name', 'last_name'],'ilike', "%$term%");
                    })
                    ->orWhereHas('assignedDoctor', function ($query) use ($term) {
                        $query->whereAny(['name', 'last_name'],'ilike', "%$term%");
                    })
                    ->orWhereHas('lastReviewerDoctor', function ($query) use ($term) {
                        $query->whereAny(['name', 'last_name'],'ilike', "%$term%");
                    });
            })
            ->paginate($per_page)->withQueryString());
    }
}
