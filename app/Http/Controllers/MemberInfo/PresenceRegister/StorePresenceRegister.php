<?php

namespace App\Http\Controllers\MemberInfo\PresenceRegister;

use App\Data\PresenceRegisterDto;
use App\Models\PresenceRegister;

class StorePresenceRegister
{
    public function __invoke(PresenceRegister $register,PresenceRegisterDto $dto)
    {

        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');

        $register->fill($dto->toArray());

        if ($register->isDirty('check_in_time') && $register->check_in_time) {
            $register->is_late = $register->check_in_time > $register->date->setTime(8, 0);
        }

        if ($register->isDirty('check_out_time') && $register->check_out_time) {
            $register->is_early_departure = $register->check_out_time < $register->date->setTime(16, 0);
        }

        $register->save();

        flash()->addSuccess(__('Presence register updated successfully'));

        return inertia_location(route('members.presence-register.list'));
    }
}
