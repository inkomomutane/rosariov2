<?php

namespace App\Http\Controllers\Cases;

use App\Data\AlertDto;
use App\Models\CasePricing;

class DeleteCasePricing
{
    public function __invoke(CasePricing $pricing)
    {
        $pricing->delete();
        return redirect()->back()->with('messages', AlertDto::success(__('Delete')));
    }
}
