<?php

namespace App\Http\Controllers\Cases;

use App\Data\FullPatientCaseDto;
use App\Services\CaseService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnassignedCasesController
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Cases/UnassignedCases', [
            'cases' => $this->handle($request->input('term', ''), $request->input('per_page', 12))
        ]);
    }

    public function handle(?string $term = '', ?int $per_page = 12)
    {
        return FullPatientCaseDto::collect(
            CaseService::getUnassignedCases($term)
                ->paginate($per_page)
                ->withQueryString()
        );
    }
}

