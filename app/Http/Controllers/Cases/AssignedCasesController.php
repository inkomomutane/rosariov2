<?php

namespace App\Http\Controllers\Cases;

use App\Data\FullPatientCaseDto;
use App\Services\CaseService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssignedCasesController
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Cases/AssignedCases', [
            'cases' => $this->handle($request->input('term', ''), $request->input('per_page', 12))
        ]);
    }

    public function handle(?string $term = '', ?int $per_page = 12)
    {
        $doctor = auth()->user()->person;

        if (!$doctor) {
            return collect([]);
        }

        return FullPatientCaseDto::collect(
            CaseService::getAssignedCases($doctor, $term)
                ->paginate($per_page)
                ->withQueryString()
        );
    }
}

