<?php

namespace App\Enum;

enum CaseType : string
{

    use HasToObjectValues;
    use HasToArrayValues;


    case RAY_X = 'ray_x';
    case ECOGRAPHY = 'ecography';
    case TAC = 'tac';
    case MRI = 'mri';
    case LABORATORY = 'laboratory';
    case OTHER = 'other';
}
