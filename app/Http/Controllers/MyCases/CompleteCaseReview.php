<?php

namespace App\Http\Controllers\MyCases;

use App\Enum\PatientCaseStatus;
use App\Enum\ReviewStatus;
use App\Models\CaseReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompleteCaseReview
{
    /**
     * @throws \Throwable
     */
    public function __invoke(
        Request $request,
        CaseReview $review
    ): RedirectResponse {
        $validated = $request->validate([
            'review_notes' => [
                'required',
                'string',
                'max:20000',
            ],
        ]);

        $person = $request->user()->person;

        abort_unless(
            $person?->is_doctor &&
            $review->reviewer_doctor_id === $person->id,
            403
        );

        DB::transaction(static function () use ($review, $validated,$person) {
            $locked = CaseReview::query()
                ->lockForUpdate()
                ->findOrFail($review->id);

            if (!in_array($locked->status->value, [
                'pending',
                'in_progress',
            ], true)) {
                throw ValidationException::withMessages([
                    'review_notes' => 'This review has already been completed or closed.',
                ]);
            }

            $locked->update([
                'review_notes' => $validated['review_notes'],
                'review_date' => now(),
                'status' => ReviewStatus::COMPLETED,
            ]);
            $locked->case()->update([
                'last_review_date' => now(),
                'last_reviewer_doctor_id' => $person->id,
                'status' => PatientCaseStatus::REVIEWED,
                'final_review' => $validated['review_notes'],
                'first_review' => $locked->case->first_review ?? $validated['review_notes'],
            ]);
        });

        return back()->with(
            'success',
            'Review completed successfully.'
        );
    }
}
