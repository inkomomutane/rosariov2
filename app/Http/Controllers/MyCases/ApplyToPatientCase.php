<?php

namespace App\Http\Controllers\MyCases;

use App\Data\AlertDto;
use App\Data\InvoiceData;
use App\Enum\PatientCaseStatus;
use App\Enum\PaymentType;
use App\Enum\ReviewStatus;
use App\Models\PatientCase;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class ApplyToPatientCase
{
    public function __invoke(PatientCase $case): RedirectResponse
    {
        $person = auth()->user()->person;

        if (!$person?->is_doctor) {
            return redirect()->back()->with(
                'messages',
                AlertDto::error(__('Only doctors can apply to patient cases.'))
            );
        }

        try {
            DB::transaction(static function () use ($case, $person): void {
                $case = PatientCase::query()
                    ->lockForUpdate()
                    ->findOrFail($case->id);

                if ($case->assigned_doctor_id !== null) {
                    throw new RuntimeException(
                        __('This case is already assigned to a doctor.')
                    );
                }

                $case->update([
                    'status' => PatientCaseStatus::UNDER_REVIEW,
                    'assigned_doctor_id' => $person->id,
                    'last_reviewer_doctor_id' => $person->id,
                ]);

                $case->reviews()->create([
                    'reviewer_doctor_id' => $person->id,
                    'status' => ReviewStatus::PENDING,
                ]);

                $invoice = InvoiceData::fromModel($case->invoice);
                Payment::create([
                    'user_id' => auth()->user()->id,
                    'invoice_code' => $invoice->code,
                    'credit' => '0.00',
                    'debit' => $invoice->total,
                    'reference' => "Pagamento ao médico {$person->name} para o caso {$case->case_code}",
                    'description' => "Pagamento ao médico {$person->name} para o caso {$case->case_code}",
                    'document_date' => now(),
                    'due_date' => now()->addDays(0),
                    'payment_date' => now(),
                    'transaction_code' => null,
                    'posted_date' => now(),
                    'type' => PaymentType::Transfer,
                ]);


            }, 3);

            return redirect()->back()->with(
                'messages',
                AlertDto::success(__('Successfully applied to patient case.'))
            );

        } catch (Throwable $exception) {
            Log::error('Failed to apply to patient case', [
                'case_id' => $case->id,
                'user_id' => auth()->id(),
                'error' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            return redirect()->back()->with(
                'messages',
                AlertDto::error(
                    $exception instanceof RuntimeException
                        ? $exception->getMessage()
                        : __('Failed to apply to patient case.')
                )
            );
        }
    }
}
