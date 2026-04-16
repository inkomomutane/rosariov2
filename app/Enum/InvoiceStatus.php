<?php

namespace App\Enum;

enum InvoiceStatus : string
{


    use HasToObjectValues;
    use HasToArrayValues;

    case DRAFT = 'draft';
    case ISSUED = 'issued';
    case PARTIALLY_PAID = 'partially_paid';
    case PAID = 'paid';
    case REFUNDED = 'refunded';
    case OVERDUE = 'overdue';
    case CANCELED = 'canceled';
}
