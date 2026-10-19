<?php

namespace App\Services;

use App\Models\PatientCase;
use App\Enum\PatientCaseStatus;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;

class CaseService
{

    public static function getPatientCases(Person $patient, ?string $term = null): Builder
    {
        $query = PatientCase::query()
            ->where('person_id', $patient->id)
            ->with(['patient', 'requesterDoctor', 'assignedDoctor', 'lastReviewerDoctor'])
            ->orderBy('created_at', 'desc');

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->whereAny([
                    'case_code',
                    'priority',
                    'title',
                    'description',
                    'case_type',
                ], 'ilike', "%$term%");
            });
        }

        return $query;
    }
}

