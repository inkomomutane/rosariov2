<?php

namespace App\Enum;

enum ReviewStatus : string
{


    use HasToArrayValues;
    use HasToObjectValues;


    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}
