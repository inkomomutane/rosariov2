<?php

namespace App\Http\Controllers\Cases;

use App\Data\FullPatientCaseDto;
use App\Services\CaseService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientCasesListController
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Cases/PatientCasesList', [
            'cases' => $this->handle($request->input('term', ''), $request->input('per_page', 12))
        ]);
    }

    public function handle(?string $term = '', ?int $per_page = 12)
    {
        $patient = auth()->user()->person?->patient;

        if (!$patient) {
            return collect([]);
        }

        return FullPatientCaseDto::collect(
            CaseService::getPatientCases($patient, $term)
                ->paginate($per_page)
                ->withQueryString()
        );
    }
}

