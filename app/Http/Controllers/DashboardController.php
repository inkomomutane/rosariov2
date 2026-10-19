<?php

namespace App\Http\Controllers;

use App\Data\FullPatientCaseDto;
use App\Data\UserFrontData;
use App\Enum\CaseType;
use App\Enum\Urgency;
use App\Services\DoctorCaseServices;
use App\Services\PatientCaseServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $person = $user->person;

        if (!$person) {
            return redirect()->route('dashboard.user-person-edit');
        }

        $search = $request->input('search', '');

        $shared = [
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
        ];

        if ($person->is_doctor) {
            return Inertia::render('MedicalDashboard', [
                ...$shared,

                'cases' => Inertia::scroll(
                    static fn () => FullPatientCaseDto::collect(
                        DoctorCaseServices::getAvailableCases($search)
                            ->paginate(15, pageName: 'cases_page')
                    )
                ),
            ]);
        }

        return Inertia::render('PatientDashboard', [
            ...$shared,

            'cases' => Inertia::scroll(
                static fn () => FullPatientCaseDto::collect(
                    PatientCaseServices::getPatientCases($person, $search)
                        ->paginate(15)
                )
            ),
        ]);
    }
}
