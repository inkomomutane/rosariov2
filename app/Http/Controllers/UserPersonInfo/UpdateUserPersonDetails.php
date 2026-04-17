<?php

namespace App\Http\Controllers\UserPersonInfo;

use App\Data\AlertDto;
use App\Data\PersonDto;

class UpdateUserPersonDetails
{
    public function __invoke(PersonDto $personDto)
    {
        $person = auth()->user()->person()->firstOrCreate([
             'user_id' => auth()->id()
        ],[
            'name' => auth()->user()->name,
            'last_name' => ''
        ]);
        $person->update($personDto->toArray());
        return redirect()->route('dashboard.user-person-edit')->with('messages', AlertDto::success(__('Update')));
    }
}
