<?php

namespace App\Services;

use App\Models\PatientCase;
use App\Enum\PatientCaseStatus;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;

class CaseService
{
    public static function acceptCase(PatientCase $case, Person $doctor): bool
    {
        if ($case->status !== PatientCaseStatus::AWAITING_ASSIGNMENT) {
            return false;
        }

        $case->update([
            'assigned_doctor_id' => $doctor->id,
            'status' => PatientCaseStatus::ASSIGNED,
        ]);

        return true;
    }

    public static function completeCase(PatientCase $case, Person $doctor, string $finalReview): bool
    {
        if ($case->assigned_doctor_id !== $doctor->id && $case->requester_doctor_id !== $doctor->id) {
            return false;
        }

        $case->update([
            'final_review' => $finalReview,
            'last_review_date' => now(),
            'last_reviewer_doctor_id' => $doctor->id,
            'status' => PatientCaseStatus::CLOSED,
        ]);

        return true;
    }

    public static function rejectCase(PatientCase $case, Person $doctor, ?string $reassignToDoctorId = null): bool
    {
        if ($case->assigned_doctor_id !== $doctor->id) {
            return false;
        }

        $case->update([
            'assigned_doctor_id' => $reassignToDoctorId,
            'status' => PatientCaseStatus::AWAITING_ASSIGNMENT,
        ]);

        return true;
    }

    public static function requestMoreInfo(PatientCase $case, Person $doctor, string $infoRequest): bool
    {
        if ($case->assigned_doctor_id !== $doctor->id) {
            return false;
        }

        $case->update([
            'first_review' => $infoRequest,
            'first_review_date' => now(),
        ]);

        return true;
    }

    public static function doSecondReview(PatientCase $case, Person $doctor, string $reviewNotes): bool
    {
        $newStatus = $case->status === PatientCaseStatus::REVIEWED
            ? PatientCaseStatus::IN_SECONDARY_REVIEW
            : PatientCaseStatus::SECONDARY_REVIEWED;

        $case->update([
            'final_review' => $reviewNotes,
            'last_review_date' => now(),
            'last_reviewer_doctor_id' => $doctor->id,
            'status' => $newStatus,
        ]);

        return true;
    }

    public static function cancelCase(PatientCase $case): bool
    {
        $case->update([
            'status' => PatientCaseStatus::CANCELLED,
        ]);

        return true;
    }

    public static function getAssignedCases(Person $doctor, ?string $term = null)
    {
        $query = PatientCase::query()
            ->where('assigned_doctor_id', $doctor->id)
            ->whereIn('status', [
                PatientCaseStatus::ASSIGNED,
                PatientCaseStatus::UNDER_REVIEW,
                PatientCaseStatus::REVIEWED,
                PatientCaseStatus::IN_SECONDARY_REVIEW,
            ])
            ->with(['patient', 'requesterDoctor', 'assignedDoctor', 'lastReviewerDoctor']);

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->whereAny([
                    'case_code',
                    'priority',
                    'title',
                    'description',
                    'case_type',
                ], 'ilike', "%$term%")
                    ->orWhereHas('patient', function ($q) use ($term) {
                        $q->whereAny(['name', 'last_name'], 'ilike', "%$term%");
                    });
            });
        }

        return $query;
    }

    public static function getUnassignedCases(?string $term = null)
    {
        $query = PatientCase::query()
            ->whereNull('assigned_doctor_id')
            ->orWhere('status', PatientCaseStatus::AWAITING_ASSIGNMENT)
            ->with(['patient', 'requesterDoctor', 'assignedDoctor', 'lastReviewerDoctor']);

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->whereAny([
                    'case_code',
                    'priority',
                    'title',
                    'description',
                    'case_type',
                ], 'ilike', "%$term%")
                    ->orWhereHas('patient', function ($q) use ($term) {
                        $q->whereAny(['name', 'last_name'], 'ilike', "%$term%");
                    });
            });
        }

        return $query;
    }

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

