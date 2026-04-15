<?php

namespace App\Http\Controllers\MemberInfo\DisciplinaryRecords;

use App\Data\AlertDto;
use App\Models\DisciplinaryRecord;
use App\Models\User;

class DeleteMemberDisciplinaryRecord
{
    public function __invoke(User $member, DisciplinaryRecord $disciplinaryRecord)
    {
        $disciplinaryRecord->delete();
        return redirect()->route('member.disciplinary-record.list', $member->id)->with('messages', AlertDto::success(__('Disciplinary record deleted successfully')));
    }
}
