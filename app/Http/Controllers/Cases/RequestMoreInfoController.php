<?php

namespace App\Http\Controllers\Cases;

use App\Data\AlertDto;
use App\Models\PatientCase;
use App\Services\CaseService;
use Illuminate\Http\Request;

class RequestMoreInfoController
{
    public function __invoke(PatientCase $case, Request $request)
    {
        $request->validate([
            'info_request' => 'required|string|min:10',
        ]);

        $doctor = auth()->user()->person?->doctor;

        if (!$doctor) {
            return redirect()->back()->with('messages', AlertDto::error(__('You must be a doctor to request information')));
        }

        if (CaseService::requestMoreInfo($case, $doctor, $request->input('info_request'))) {
            return redirect()->back()->with('messages', AlertDto::success(__('Information request sent successfully')));
        }

        return redirect()->back()->with('messages', AlertDto::error(__('You are not assigned to this case')));
    }
}

