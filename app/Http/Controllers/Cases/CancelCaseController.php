<?php

namespace App\Http\Controllers\Cases;

use App\Data\AlertDto;
use App\Models\PatientCase;
use App\Services\CaseService;
use Illuminate\Http\Request;

class CancelCaseController
{
    public function __invoke(PatientCase $case, Request $request)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|min:10',
        ]);

        if (CaseService::cancelCase($case)) {
            return redirect()->back()->with('messages', AlertDto::success(__('Case cancelled successfully')));
        }

        return redirect()->back()->with('messages', AlertDto::error(__('Could not cancel case')));
    }
}

