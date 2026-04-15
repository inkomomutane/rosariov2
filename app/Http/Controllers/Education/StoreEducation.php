<?php

namespace App\Http\Controllers\Education;

use App\Data\AlertDto;
use App\Data\EducationDto;

class StoreEducation
{
    public function __invoke(EducationDto $dto)
    {
        $education = auth()->user()->educations()->create($dto->toArray());
        $education->save();
        return redirect()->route('profile.education.list')->with('messages',AlertDto::success(__('Education created successfully')));
    }
}
