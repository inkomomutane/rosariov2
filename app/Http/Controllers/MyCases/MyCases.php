<?php

namespace App\Http\Controllers\MyCases;

use App\Data\FullPatientCaseDto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MyCases
{
    public function __invoke(Request $request)
    {
        return Inertia::render('My/Cases/Index',[
            'cases' =>  $this->handle($request->input('term',''),$request->input('per_page',12))
        ]);
    }

    public function handle(?string $term = '',?int $per_page = 12) {
        return FullPatientCaseDto::collect( auth()->user()->person?->cases()
            ->with(['patient', 'requesterDoctor', 'assignedDoctor', 'lastReviewerDoctor'])
            ->when($term, function ($query, $term) {
                $query->whereAny([
                    'case_code',
                    'priority',
                    'title',
                    'description',
                    'case_type',
                    'first_review',
                    'final_review',
                    'status',
                ],'ilike', "%$term%")
                    ->orWhereHas('patient', function ($query) use ($term) {
                        $query->whereAny(['name', 'last_name',],'ilike', "%$term%");
                    })
                    ->orWhereHas('requesterDoctor', function ($query) use ($term) {
                        $query->whereAny(['name', 'last_name'],'ilike', "%$term%");
                    })
                    ->orWhereHas('assignedDoctor', function ($query) use ($term) {
                        $query->whereAny(['name', 'last_name'],'ilike', "%$term%");
                    })
                    ->orWhereHas('lastReviewerDoctor', function ($query) use ($term) {
                        $query->whereAny(['name', 'last_name'],'ilike', "%$term%");
                    });
            })
            ->paginate($per_page)->withQueryString());
    }
}
