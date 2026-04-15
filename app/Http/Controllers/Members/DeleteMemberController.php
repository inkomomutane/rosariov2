<?php

namespace App\Http\Controllers\Members;

use App\Models\Experience;
use App\Models\User;

class DeleteMemberController
{
    public function __invoke(User $member)
    {

        if($member->id === auth()->user()->id){
            return back();
        }

        $member->is_active = false;
        $member->saveQuietly();
        flash()->addSuccess(__('Member deleted successfully'));
        return inertia_location(route('prm-members'));
    }
}
