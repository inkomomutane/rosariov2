<?php

namespace App\Services;

use App\Enum\PatientCaseStatus;
use App\Models\PatientCase;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;

class DoctorCaseServices
{
    public static function getAvailableCases(?string $term = null): Builder
    {
        return self::search(
            PatientCase::query()
                ->whereNull('assigned_doctor_id')
                ->whereIn('status', [
                    PatientCaseStatus::PAID,
                    PatientCaseStatus::SECOND_REVIEW_PAID,
                ]),
            $term
        );
    }

    public static function getAssignedCases(
        Person  $doctor,
        ?string $term = null
    ): Builder
    {
        return self::search(
            PatientCase::query()
                ->where('assigned_doctor_id', $doctor->id)
                ->whereIn('status', [
                    PatientCaseStatus::UNDER_REVIEW,
                    PatientCaseStatus::REVIEWED,
                    PatientCaseStatus::IN_SECONDARY_REVIEW,
                    PatientCaseStatus::SECONDARY_REVIEWED,
                ]),
            $term
        );
    }

    private static function search(Builder $query, ?string $term): Builder
    {
        return $query
            ->with([
                'patient',
                'requesterDoctor',
                'assignedDoctor',
                'lastReviewerDoctor',
            ])
            ->when(trim($term ?? '') !== '', function (Builder $query) use ($term) {
                $query->where(function (Builder $q) use ($term) {
                    $q->whereAny([
                        'case_code',
                        'priority',
                        'title',
                        'description',
                        'case_type',
                    ], 'ilike', "%{$term}%")
                        ->orWhereHas('patient', fn(Builder $q) => $q->whereAny(
                            ['name', 'last_name'],
                            'ilike',
                            "%{$term}%"
                        )
                        );
                });
            })
            ->latest();
    }
}
