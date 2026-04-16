<?php

namespace App\Enum;

enum FormUIMap : string
{

    use HasToArrayValues;
    use HasToObjectValues;


    case USERS = 'users';
    case DOCTORS = 'doctors';
    case PATIENTS = 'patients';
    case CASES = 'cases';
    case CASE_REVIEWS = 'case_reviews';
    case INVOICES = 'invoices';
    case PAYMENTS = 'payments';
}
