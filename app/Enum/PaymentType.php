<?php

namespace App\Enum;

enum PaymentType : string
{
    use HasToArrayValues;
    use HasToObjectValues;

    case Invoice = 'invoice';

    case Payment = 'payment';
    case Withdraw = 'withdraw';
    case Refund =   'refund';
    case Adjustment = 'adjustment';

    case Transfer = 'transfer';
}
