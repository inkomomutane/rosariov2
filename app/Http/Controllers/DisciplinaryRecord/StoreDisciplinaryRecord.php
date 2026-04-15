<?php

namespace App\Http\Controllers\DisciplinaryRecord;

use App\Data\AlertDto;
use App\Data\DisciplinaryRecordDto;
use App\Models\User;

class StoreDisciplinaryRecord
{
    public function __invoke(User $user,DisciplinaryRecordDto $dto)
    {
        $disciplinaryRecord = $user->disciplinaryRecords()->create($dto->toArray());
        $disciplinaryRecord->save();
        return redirect()->route('profile.disciplinary-record.list')->with('messages', AlertDto::success(__('Disciplinary record created successfully')));
    }
}
