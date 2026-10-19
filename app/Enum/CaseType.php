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
    case MAMMOGRAPHY = 'mammography';
    case DENSITOMETRY = 'densitometry';
    case FLUOROSCOPY = 'fluoroscopy';
    case ANGIOGRAPHY = 'angiography';
    case PET_SCAN = 'pet_scan';
    case SCINTIGRAPHY = 'scintigraphy';
    case DOPPLER_ULTRASOUND = 'doppler_ultrasound';
    case ECHOCARDIOGRAPHY = 'echocardiography';
    case OBSTETRIC_ULTRASOUND = 'obstetric_ultrasound';
    case TRANSVAGINAL_ULTRASOUND = 'transvaginal_ultrasound';
    case ELASTOGRAPHY = 'elastography';
    case OTHER = 'other';
}
