<?php

namespace App\Http\Controllers\Cases;

use App\Data\CasePricingDto;
use App\Enum\CaseType;
use App\Models\CasePricing;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListCasePricing
{
    public function __invoke(Request $request)
    {
        return Inertia::render('settings/CasePricing', [
            'pricing' => $this->handle($request->input('term', ''), $request->input('per_page', 12)),
            'case_types' => CaseType::toValues()
        ]);
    }

    public function handle(?string $term = '', ?int $per_page = 12)
    {
        return CasePricingDto::collect(
                CasePricing::when($term, static function ($query) use ($term) {
                    $query->whereAny([
                         'case_type','price','review_phase'
                    ],'ilike', "%$term%");
                })
                ->paginate($per_page)
                ->withQueryString()
        );
    }
}
