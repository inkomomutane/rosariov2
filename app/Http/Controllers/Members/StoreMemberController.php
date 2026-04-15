<?php

namespace App\Http\Controllers\Members;

use App\Data\AlertDto;
use App\Data\UserDto;
use App\Models\User;

class StoreMemberController
{
    public function __invoke(UserDto $dto)
    {
        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');
        User::create($dto->toArray());
        return redirect()->back()->with('messages', AlertDto::success(__('Member created successfully')));
    }
}
