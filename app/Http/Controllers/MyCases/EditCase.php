<?php

namespace App\Http\Controllers\MyCases;

use App\Data\PatientCaseRequestDto;
use App\Enum\CaseType;
use App\Enum\Urgency;
use App\Models\PatientCase;
use Inertia\Inertia;

class EditCase
{
    public function __invoke(PatientCase $case)
    {
        return Inertia::render('My/Cases/Edit', [
            'person' => auth()->user()->person()->firstOrCreate([
                'user_id' => auth()->id()
            ], [
                'name' => auth()->user()->name,
                'last_name' => ''
            ])->only(['name', 'last_name', 'id', 'gender', 'marital_status']),
            'case_types' => CaseType::toValues(),
            'urgencies' => Urgency::toValues(),
            'case' => PatientCaseRequestDto::fromModel($case)
        ]);
    }
}
