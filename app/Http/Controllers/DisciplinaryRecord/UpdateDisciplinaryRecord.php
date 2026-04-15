<?php

namespace App\Http\Controllers\DisciplinaryRecord;

use App\Data\AlertDto;
use App\Data\DisciplinaryRecordDto;
use App\Models\DisciplinaryRecord;

class UpdateDisciplinaryRecord
{
    public function __invoke(DisciplinaryRecord $disciplinaryRecord, DisciplinaryRecordDto $dto)
    {
        $disciplinaryRecord->update($dto->toArray());
        $disciplinaryRecord->save();
        return redirect()->route('profile.disciplinary-record.list')->with('messages', AlertDto::success(__('Disciplinary record updated successfully')));
    }
}
