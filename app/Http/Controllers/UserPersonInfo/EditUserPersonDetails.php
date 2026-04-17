<?php

namespace App\Http\Controllers\UserPersonInfo;

use App\Data\PersonDto;
use Inertia\Inertia;

class EditUserPersonDetails
{
    public function __invoke()
    {
        $person = PersonDto::fromModel(auth()->user()->person()->firstOrCreate([
            'user_id' => auth()->id()
        ],[
            'name' => auth()->user()->name,
            'last_name' => ''
        ]));

        return Inertia::render('person/Index', [
            'person' => $person
        ]);
    }
}
