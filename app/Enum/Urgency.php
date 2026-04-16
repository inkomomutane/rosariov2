<?php

namespace App\Enum;

enum Urgency : string
{
    use HasToArrayValues;
    use HasToObjectValues;

    case LOW = 'low';
    case NORMAL = 'normal';
    case EMERGENCY = 'emergency';
    case CRITICAL = 'critical';

}
