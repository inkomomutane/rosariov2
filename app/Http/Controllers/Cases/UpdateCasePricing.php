<?php

namespace App\Http\Controllers\Cases;

use App\Data\AlertDto;
use App\Models\CasePricing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateCasePricing
{
    public function __invoke(CasePricing $pricing,Request $request)
    {
        $data = $request->validate([
            'case_type' => 'required|string',
            'price' => 'required|numeric',
            'review_phase' => ['required','min:1','max:5','integer',   Rule::unique('case_pricing')
                ->where(fn ($query) => $query->where('case_type', request('case_type')))],

        ]);
        $pricing->update($data);
        return redirect()->back()->with('messages', AlertDto::success(__('Update')));
    }
}
