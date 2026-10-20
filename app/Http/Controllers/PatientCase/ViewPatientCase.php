<?php

namespace App\Http\Controllers\PatientCase;

use App\Models\PatientCase;
use Inertia\Inertia;

class ViewPatientCase
{
    public function __invoke(PatientCase $case)
    {
        $case->load(['patient', 'requesterDoctor', 'assignedDoctor', 'lastReviewerDoctor', 'reviews.doctor', 'reviews.media','media']);
        return Inertia::render('PatientFullCase', [
            'case' => $case
        ]);
    }
}
