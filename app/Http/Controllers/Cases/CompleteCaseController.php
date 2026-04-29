<?php

namespace App\Http\Controllers\Cases;

use App\Data\AlertDto;
use App\Models\PatientCase;
use App\Services\CaseService;
use Illuminate\Http\Request;

class CompleteCaseController
{
    public function __invoke(PatientCase $case, Request $request)
    {
        $request->validate([
            'final_review' => 'required|string',
        ]);

        $doctor = auth()->user()->person?->doctor;

        if (!$doctor) {
            return redirect()->back()->with('messages', AlertDto::error(__('You must be a doctor to complete cases')));
        }

        if (CaseService::completeCase($case, $doctor, $request->input('final_review'))) {
            return redirect()->back()->with('messages', AlertDto::success(__('Case completed successfully')));
        }

        return redirect()->back()->with('messages', AlertDto::error(__('You are not assigned to this case')));
    }
}

