<?php

namespace App\Http\Controllers\Cases;

use App\Data\AlertDto;
use App\Models\PatientCase;
use App\Services\CaseService;
use Illuminate\Http\Request;

class AcceptCaseController
{
    public function __invoke(PatientCase $case, Request $request)
    {
        $doctor = auth()->user()->person?->doctor;

        if (!$doctor) {
            return redirect()->back()->with('messages', AlertDto::error(__('You must be a doctor to accept cases')));
        }

        if (CaseService::acceptCase($case, $doctor)) {
            return redirect()->back()->with('messages', AlertDto::success(__('Case accepted successfully')));
        }

        return redirect()->back()->with('messages', AlertDto::error(__('This case is not available for acceptance')));
    }
}

