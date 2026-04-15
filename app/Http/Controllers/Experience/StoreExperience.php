<?php

namespace App\Http\Controllers\Experience;

use App\Data\AlertDto;
use App\Data\ExperienceDto;

class StoreExperience
{
    public function __invoke(ExperienceDto $dto)
    {
        $experience = auth()->user()->experiences()->create($dto->toArray());
        $experience->save();
        return redirect()->route('profile.experience.list')->with('messages',AlertDto::success(__('Experience created successfully')));
    }
}
