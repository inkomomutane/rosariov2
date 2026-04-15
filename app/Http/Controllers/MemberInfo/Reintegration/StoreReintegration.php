<?php
namespace App\Http\Controllers\MemberInfo\Reintegration;

use App\Data\AlertDto;
use App\Data\ReintegrationDto;
use App\Models\Reintegration;

class StoreReintegration
{
    public function __invoke(ReintegrationDto $dto)
    {
        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');
         Reintegration::create($dto->toArray());
        return redirect()->route('profile.reintegration.list')->with('messages',AlertDto::success(__('Reintegration created successfully')));
    }
}

