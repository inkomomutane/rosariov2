<?php

namespace App\Http\Controllers\MemberInfo\Experience;

use App\Data\AlertDto;
use App\Data\ExperienceDto;
use App\Models\User;

class StoreMemberExperience
{
    public function __invoke(ExperienceDto $dto, User $member)
    {
        $experience = $member->experiences()->create($dto->toArray());
        $experience->save();
        return redirect()->route('member.experience.list',  $member->id)->with('messages', AlertDto::success(__('Experience created successfully')));
    }
}
