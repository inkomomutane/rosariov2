<?php

namespace App\Http\Controllers\Experience;

use App\Data\AlertDto;
use App\Data\ExperienceDto;
use App\Models\Experience;

class UpdateExperience
{

    public function __invoke(Experience $experience,ExperienceDto $dto)
    {
        $experience->update($dto->toArray());
        $experience->save();
        return redirect()->route('profile.experience.list')->with('messages',AlertDto::success(__('Experience updated successfully')));
    }
}
