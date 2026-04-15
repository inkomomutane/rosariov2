<?php

namespace App\Http\Controllers\MemberInfo\PresenceRegister;

use App\Models\PresenceRegister;
use App\Models\User;
use Carbon\Carbon;

class CreatePresenceSheet
{
    public function __invoke(string $date){
        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');

        try {
            $dateModel = Carbon::createFromFormat('Y-m-d', $date);
        }catch (\Exception $e){
            $dateModel = today();
        }

         if(PresenceRegister::where('date', $dateModel)->exists()){
            flash()->addError(__('A presence register for this date already exists.'));
            return back();
        }

        # get all users and generate a presence sheet for each user for the given date
        $users = User::all();
        foreach ($users as $user) {
            $user->presenceRegisters()->create([
                'date' => $dateModel,
                'check_in_time' => $dateModel->endOfDay(),
                'check_out_time' => null,
                'check_in_location' => null,
                'check_out_location' => null,
                'notes' => null,
                'is_late' => true,
                'is_early_departure' => false,
                'total_hours_minutes' => null,
            ]);
        }

        flash()->addSuccess(__('Presence sheet created successfully for :date', ['date' => $dateModel->format('Y-m-d')]));
        return back();
    }
}
