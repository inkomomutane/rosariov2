<?php

namespace App\Http\Controllers\MemberInfo\DisciplinaryRecords;

use App\Data\AlertDto;
use App\Data\DisciplinaryRecordDto;
use App\Models\DisciplinaryRecord;
use App\Models\User;

class UpdateMemberDisciplinaryRecord
{
    public function __invoke(DisciplinaryRecordDto $dto, User $member, DisciplinaryRecord $disciplinaryRecord)
    {
        $disciplinaryRecord->update($dto->toArray());
        return redirect()->route('member.disciplinary-record.list', $member->id)->with('messages', AlertDto::success(__('Disciplinary record updated successfully')));
    }
}
