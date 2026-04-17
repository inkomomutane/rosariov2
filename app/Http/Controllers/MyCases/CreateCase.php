<?php

namespace App\Http\Controllers\MyCases;

use App\Enum\CaseType;
use App\Enum\Urgency;
use Inertia\Inertia;

class CreateCase
{
    public function __invoke()
    {
        return Inertia::render('My/Cases/Create', [
            'person' => auth()->user()->person()->firstOrCreate([
                'user_id' => auth()->id()
            ], [
                'name' => auth()->user()->name,
                'last_name' => ''
            ])->only(['name', 'last_name', 'id', 'gender', 'marital_status']),
            'case_types' => CaseType::toValues(),
            'urgencies' => Urgency::toValues()
        ]);
    }
}
