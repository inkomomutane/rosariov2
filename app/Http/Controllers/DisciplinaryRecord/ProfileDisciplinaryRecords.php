<?php

namespace App\Http\Controllers\DisciplinaryRecord;

use Inertia\Inertia;
use Inertia\Response;

class ProfileDisciplinaryRecords
{
    public function __invoke(): Response
    {
        return Inertia::render('DisciplinaryRecord/ProfileDisciplinaryRecords', [
            'disciplinary_records' => ListDisciplinaryRecord::handle(auth()->user(),request()->search),
        ]);
    }
}
