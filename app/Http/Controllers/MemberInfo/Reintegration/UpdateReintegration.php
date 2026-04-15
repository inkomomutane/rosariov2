<?php
namespace App\Http\Controllers\MemberInfo\Reintegration;

use App\Data\AlertDto;
use App\Data\ReintegrationDto;
use App\Models\Reintegration;

class UpdateReintegration
{
    public function __invoke(Reintegration $reintegration, ReintegrationDto $dto)
    {
        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');
        $reintegration->update($dto->toArray());
        $reintegration->save();
        return redirect()->route('profile.reintegration.list')->with('messages',AlertDto::success(__('Reintegration updated successfully')));
    }
}

