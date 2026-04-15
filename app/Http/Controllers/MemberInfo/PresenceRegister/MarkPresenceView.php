<?php

namespace App\Http\Controllers\MemberInfo\PresenceRegister;

use Inertia\Inertia;

class MarkPresenceView
{
    public function __invoke()
    {
       return Inertia::render('MemberPresence/Mark',[
           'date' => now()->format('Y-m-d'),
       ]);
    }
}
