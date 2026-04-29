<?php

namespace App\Http\Controllers\UserPersonInfo;

use App\Data\DoctorDto;
use Inertia\Inertia;

class EditUserDoctorDetails
{
    public function __invoke()
    {
        $doctor = DoctorDto::fromModel(auth()->user()->person()->firstOrCreate([
            'user_id' => auth()->id()
        ],[
            'name' => auth()->user()->name,
            'last_name' => ''
        ]));

        return Inertia::render('person/DoctorDetails', [
            'doctor' => $doctor
        ]);
    }
}
