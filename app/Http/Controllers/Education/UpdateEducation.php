<?php

namespace App\Http\Controllers\Education;

use App\Data\AlertDto;
use App\Data\EducationDto;
use App\Models\Education;

class UpdateEducation
{

    public function __invoke(Education $education,EducationDto $dto)
    {
        $education->update($dto->toArray());
        $education->save();
        return redirect()->route('profile.education.list')->with('messages',AlertDto::success(__('Education updated successfully')));
    }
}
