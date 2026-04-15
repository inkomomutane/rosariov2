<?php

namespace App\Http\Controllers\MemberInfo\Education;

use App\Data\AlertDto;
use App\Data\EducationDto;
use App\Models\User;

class StoreMemberEducation
{
    public function __invoke(EducationDto $dto, User $member)
    {
        $education = $member->educations()->create($dto->toArray());
        $education->save();
        return redirect()->route('member.education.list', $member->id)->with('messages', AlertDto::success(__('Education created successfully')));
    }
}
