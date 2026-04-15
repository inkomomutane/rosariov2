<?php

namespace App\Http\Controllers\Education;

use App\Models\Education;

class DeleteEducation
{
    public function __invoke(Education $education)
    {
        $education->delete();
        flash()->addSuccess(__('Education deleted successfully'));
        return inertia_location(route('profile.education.list'));
    }
}
