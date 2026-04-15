<?php

namespace App\Http\Controllers\MemberInfo\Experience;

use App\Data\AlertDto;
use App\Data\ExperienceDto;
use App\Models\Experience;
use App\Models\User;

class UpdateMemberExperience
{
    public function __invoke(User $member, Experience $experience, ExperienceDto $dto)
    {
        $experience->update($dto->toArray());
        $experience->save();
        return redirect()->route('member.experience.list',  $member->id)->with('messages', AlertDto::success(__('Experience updated successfully')));
    }
}
