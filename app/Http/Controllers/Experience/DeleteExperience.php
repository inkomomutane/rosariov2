<?php

namespace App\Http\Controllers\Experience;

use App\Models\Experience;

class DeleteExperience
{
    public function __invoke(Experience $experience)
    {
        $experience->delete();
        flash()->addSuccess(__('Experience deleted successfully'));
        return inertia_location(route('profile.experience.list'));
    }
}
