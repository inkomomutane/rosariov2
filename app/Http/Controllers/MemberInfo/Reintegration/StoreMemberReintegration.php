<?php

namespace App\Http\Controllers\MemberInfo\Reintegration;

use App\Data\AlertDto;
use App\Data\ReintegrationDto;
use App\Models\User;

class StoreMemberReintegration
{
    public function __invoke(ReintegrationDto $dto, User $member)
    {
        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');
        $member->reintegrations()->create($dto->toArray());
        return redirect()->route('member.reintegration.list',  $member->id)->with('messages', AlertDto::success(__('Reintegration created successfully')));
    }
}
