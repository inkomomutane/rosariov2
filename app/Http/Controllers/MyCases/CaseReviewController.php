<?php

namespace App\Http\Controllers\MyCases;

use App\Models\PatientCase;
use Inertia\Inertia;

class CaseReviewController
{
    public function __invoke(PatientCase $case)
    {
        $case->load(['patient', 'requesterDoctor', 'assignedDoctor', 'lastReviewerDoctor', 'reviews.doctor', 'reviews.media','media']);

        return Inertia::render('My/Cases/Review',[
            'case' => $case,
            'review' => $case->reviews()->where('reviewer_doctor_id', auth()->user()->person->id)->first()
        ]);
    }
}
