<?php

namespace App\Http\Controllers\MemberInfo\DisciplinaryRecords;

use App\Data\AlertDto;
use App\Data\DisciplinaryRecordDto;
use App\Models\User;

class StoreMemberDisciplinaryRecord
{
    public function __invoke(DisciplinaryRecordDto $dto, User $member)
    {
        $disciplinaryRecord = $member->disciplinaryRecords()->create($dto->toArray());
        $disciplinaryRecord->save();
        return redirect()->route('member.disciplinary-record.list', $member->id)->with('messages', AlertDto::success(__('Disciplinary record created successfully')));
    }
}
