<?php

namespace App\Http\Controllers\FormUI;

use App\Data\ColumnOrderDto;
use App\Enum\FormUIMap;
use App\Models\UIFields;
use Illuminate\Http\Request;

class UpdateFormUIController
{
    public function __invoke(Request $request, FormUIMap $type): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'visible_fields' => 'array',
            'visible_fields.*.field_name' => 'string',
            'visible_fields.*.order' => 'integer',
            'visible_fields.*.showing' => 'boolean',
        ]);

        $query =  UIFields::query()->where('user_id', auth()->user()->id)->where('model_type', $type->value);

        if($query->exists()) {
            $query->update([
                'columns_order' => ColumnOrderDto::collect($data['visible_fields']),
            ]);
        }else{
            $query->create([
                'user_id' => auth()->user()->id,
                'model_type' => $type->value,
                'fields' => [],
                'selected_fields' => [],
                'columns_order' => ColumnOrderDto::collect($data['visible_fields']),
                'hidden_fields' => [],
            ]);
        }

        return response()->json(['success' => true]);
    }
}
