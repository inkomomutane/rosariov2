<?php

namespace App\Enum;

enum PatientCaseStatus : string
{
    use HasToArrayValues;
    use HasToObjectValues;

    case SUBMITTED = 'submitted';
    case PAID = 'paid';
    case UNDER_REVIEW = 'under_review';
    case REVIEWED = 'reviewed';

    case CLOSED = 'closed';
    case REOPENED = 'reopened';
    case CANCELLED = 'cancelled';

    case SECOND_REVIEW_SUBMITTED = 'second_review_submitted';
    case SECOND_REVIEW_PAID = 'second_review_paid';

    case IN_SECONDARY_REVIEW = 'in_secondary_review';
    case SECONDARY_REVIEWED = 'secondary_reviewed';
}
