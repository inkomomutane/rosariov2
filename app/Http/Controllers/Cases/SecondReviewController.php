<?php

namespace App\Http\Controllers\Cases;

use App\Data\AlertDto;
use App\Models\PatientCase;
use App\Services\CaseService;
use Illuminate\Http\Request;

class SecondReviewController
{
    public function __invoke(PatientCase $case, Request $request)
    {
        $request->validate([
            'second_review_notes' => 'required|string',
        ]);

        $doctor = auth()->user()->person?->doctor;

        if (!$doctor) {
            return redirect()->back()->with('messages', AlertDto::error(__('You must be a doctor to perform reviews')));
        }

        if (CaseService::doSecondReview($case, $doctor, $request->input('second_review_notes'))) {
            return redirect()->back()->with('messages', AlertDto::success(__('Second review completed successfully')));
        }

        return redirect()->back()->with('messages', AlertDto::error(__('Could not complete second review')));
    }
}

