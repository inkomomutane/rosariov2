<?php

namespace App\Http\Controllers\MemberInfo\Profile;

use App\Data\AlertDto;
use App\Data\UserDto;
use App\Models\User;

class UpdateMemberBaseInfo
{
    public function __invoke(User $member,UserDto $dto)
    {
        $member->update($dto->toArray());
        $member->save();
        return redirect()->route('member.edit-base-info',[
            'member'=> $member->id
        ])->with('messages', AlertDto::success(__('Profile updated successfully')));
    }
}
