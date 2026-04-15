<?php

namespace App\Http\Controllers\DisciplinaryRecord;

use App\Models\DisciplinaryRecord;

class DeleteDisciplinaryRecord
{
    public function __invoke(DisciplinaryRecord $disciplinaryRecord)
    {
        $disciplinaryRecord->delete();
        flash()->addSuccess(__('Disciplinary record deleted successfully'));
        return inertia_location(route('profile.disciplinary-record.list'));
    }
}
