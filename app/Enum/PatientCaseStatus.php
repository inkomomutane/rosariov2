<?php

namespace App\Enum;

enum PatientCaseStatus : string
{
    use HasToArrayValues;
    use HasToObjectValues;

    case SUBMITTED = 'submitted';
    case AWAITING_ASSIGNMENT = 'awaiting_assignment';
    case ASSIGNED = 'assigned';
    case UNDER_REVIEW = 'under_review';
    case REVIEWED = 'reviewed';
    case IN_SECONDARY_REVIEW = 'in_secondary_review';
    case SECONDARY_REVIEWED = 'secondary_reviewed';
    case CLOSED = 'closed';
    case REOPENED = 'reopened';
    case CANCELLED = 'cancelled';
}
