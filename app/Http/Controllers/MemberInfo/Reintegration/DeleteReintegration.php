<?php
namespace App\Http\Controllers\MemberInfo\Reintegration;

use App\Models\Reintegration;

class DeleteReintegration
{
    public function __invoke(Reintegration $reintegration)
    {

        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');

        $reintegration->delete();
        flash()->addSuccess(__('Reintegration deleted successfully'));
        return inertia_location(route('profile.reintegration.list'));
    }
}

