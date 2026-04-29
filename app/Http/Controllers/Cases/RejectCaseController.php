<?php

namespace App\Http\Controllers\Cases;

use App\Data\AlertDto;
use App\Models\PatientCase;
use App\Services\CaseService;
use Illuminate\Http\Request;

class RejectCaseController
{
    public function __invoke(PatientCase $case, Request $request)
    {
        $request->validate([
            'reassign_to_doctor_id' => 'nullable|uuid|exists:doctors,id',
        ]);

        $doctor = auth()->user()->person?->doctor;

        if (!$doctor) {
            return redirect()->back()->with('messages', AlertDto::error(__('You must be a doctor to reject cases')));
        }

        $newAssignedDoctorId = $request->input('reassign_to_doctor_id');

        if (CaseService::rejectCase($case, $doctor, $newAssignedDoctorId)) {
            return redirect()->back()->with('messages', AlertDto::success(__('Case rejected successfully')));
        }

        return redirect()->back()->with('messages', AlertDto::error(__('You can only reject your assigned cases')));
    }
}

