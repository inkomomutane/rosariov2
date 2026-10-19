<?php

namespace App\Services;

use App\Models\PatientCase;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;

class PatientCaseServices
{
    public static function getPatientCases(
        Person  $patient,
        ?string $term = null
    ): Builder
    {
        return PatientCase::query()
            ->where('person_id', $patient->id)
            ->with([
                'patient',
                'requesterDoctor',
                'assignedDoctor',
                'lastReviewerDoctor',
            ])
            ->when(trim($term ?? '') !== '', function (Builder $query) use ($term) {
                $query->whereAny([
                    'case_code',
                    'priority',
                    'title',
                    'description',
                    'case_type',
                ], 'ilike', "%{$term}%");
            })
            ->latest();
    }
}
