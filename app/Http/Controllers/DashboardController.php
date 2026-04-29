<?php

namespace App\Http\Controllers;

use App\Data\FullPatientCaseDto;
use App\Data\ProfileDto;
use App\Models\Payment;
use App\Services\CaseService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $person = $user->person;

        if (!$person) {
            return redirect()->route('dashboard.user-person-edit');
        }


        if ($person->is_doctor) {
            // Doctor Dashboard
            $assignedCases = FullPatientCaseDto::collect(
                CaseService::getAssignedCases($person)
                    ->paginate(15)
            );

            $balance = Payment::where('user_id', $user->id)->sum('credit') -
                       Payment::where('user_id', $user->id)->sum('debit');

            return Inertia::render('Dashboard', [
                'userType' => 'doctor',
                'cases' => Inertia::scroll(fn() => $assignedCases),
                'user' => ProfileDto::from($user),
                'doctorStats' => [
                    'assigned_cases' => \App\Models\PatientCase::where('assigned_doctor_id', $person->id)->count(),
                    'balance' => $balance,
                    'total_reviews' => \App\Models\PatientCase::where('last_reviewer_doctor_id', $person->id)->count(),
                ],
            ]);
        }

        // Patient Dashboard
        $patientCases = FullPatientCaseDto::collect(
            CaseService::getPatientCases($person)
                ->paginate(15)
        );

        return Inertia::render('Dashboard', [
            'userType' => 'patient',
            'cases' => Inertia::scroll(fn() => $patientCases),
            'user' =>ProfileDto::from($user),
            'patientStats' => [
                'total_cases' => $person->cases()->count() ?? 0,
                'pending_cases' => $person->cases()->where('status', '!=', 'closed')->count() ?? 0,
            ],
        ]);
    }
}

