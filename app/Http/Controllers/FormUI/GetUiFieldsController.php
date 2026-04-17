<?php

namespace App\Http\Controllers\FormUI;

use App\Enum\FormUIMap;
use App\Models\UIFields;

class GetUiFieldsController
{
    public function __invoke(FormUIMap $type): \Illuminate\Http\JsonResponse
    {
        return response()->json(UIFields::whereUserId(auth()->user()->id)->whereModelType($type->value)?->first()?->columns_order ?? []);
    }
}
