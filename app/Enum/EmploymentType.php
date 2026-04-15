<?php

namespace App\Enum;

enum EmploymentType : string
{
    use HasToObjectValues;
    use HasToArrayValues;

    case FULL_TIME = 'full_time';
    case PART_TIME = 'part_time';
    case CONTRACT = 'contract';
    case INTERNSHIP = 'internship';
    case VOLUNTEER = 'volunteer';
    case TEMPORARY = 'temporary';
    case SEASONAL = 'seasonal';
    case FREELANCE = 'freelance';
}
