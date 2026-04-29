<?php

namespace App\Http\Controllers\UserPersonInfo;

use App\Data\AlertDto;
use App\Data\DoctorDto;
use App\Data\PersonDto;

class UpdateDoctorDetails
{
    public function __invoke(DoctorDto $doctorDto)
    {
        $person = auth()->user()->person()->firstOrCreate([
             'user_id' => auth()->id()
        ],[
            'name' => auth()->user()->name,
            'last_name' => ''
        ]);
        $person->update(array_merge($doctorDto->toArray(),[
             'is_doctor' => true
        ]));
        return redirect()->route('dashboard.user-doctor-edit')->with('messages', AlertDto::success(__('Update')));
    }
}
