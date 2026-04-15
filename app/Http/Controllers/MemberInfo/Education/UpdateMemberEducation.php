<?php

namespace App\Http\Controllers\MemberInfo\Education;

use App\Data\AlertDto;
use App\Data\EducationDto;
use App\Models\Education;
use App\Models\User;

class UpdateMemberEducation
{
    public function __invoke(User $member, Education $education, EducationDto $dto)
    {
        $education->update($dto->toArray());
        $education->save();
        return redirect()->route('member.education.list',  $member->id)->with('messages', AlertDto::success(__('Education updated successfully')));
    }
}
