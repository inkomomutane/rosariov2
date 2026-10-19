<?php

namespace App\Http\Controllers\MyCases;

use App\Data\AlertDto;
use App\Data\PatientCaseRequestDto;
use App\Enum\InvoiceStatus;
use App\Enum\PatientCaseStatus;
use App\Enum\PaymentType;
use App\Models\CasePricing;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class StoreMyCase
{
    public function __invoke(PatientCaseRequestDto $dto): RedirectResponse
    {
        try {
            DB::transaction(static function () use ($dto): void {
                $user = auth()->user();

                $person = $user->person()->firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name,
                        'last_name' => '',
                    ]
                );

                $case = $person->cases()->create([
                    'case_code' => 'CASE-' . Str::ulid(),
                    'person_id' => $person->id,
                    'priority' => $dto->priority,
                    'title' => $dto->title,
                    'description' => $dto->description,
                    'case_type' => $dto->case_type,
                    'first_review' => null,
                    'final_review' => null,
                    'case_date' => now(),
                    'first_review_date' => null,
                    'last_review_date' => null,
                    'requester_doctor_id' => null,
                    'status' => PatientCaseStatus::SUBMITTED,
                    'assigned_doctor_id' => null,
                    'last_reviewer_doctor_id' => null,
                ]);

                $case->removeAttachments();
                $case->addAttachments($dto->attachments);

                $price = CasePricing::query()
                    ->whereCaseType($case->case_type)
                    ->whereReviewPhase(1)
                    ->first()
                    ?->price;

                if ($price === null) {
                    throw new RuntimeException(
                        __('No pricing found for this case type')
                    );
                }

                $invoice = Invoice::create([
                    'code' => 'INV-' . Str::ulid(),
                    'issue_date' => now(),
                    'due_date' => now()->addDays(30),
                    'paid_date' => null,
                    'cancelled_date' => null,
                    'user_id' => $user->id,
                    'status' => InvoiceStatus::ISSUED,
                    'reference' => __('Invoice'),
                    'notes' => '',
                    'patient_case_id' => $case->id,
                ]);

                $invoice->rows()->create([
                    'invoice_code' => $invoice->code,
                    'description' => $case->case_code,
                    'due_date' => $invoice->due_date,
                    'quantity' => 1,
                    'price' => $price,
                    'discount' => '0.00',
                    'tax' => '0.00',
                    'subtotal' => $price,
                    'total' => $price,
                    'paid' => '0.00',
                    'remaining' => $price,
                    'due' => '0',
                ]);

                Payment::create([
                    'user_id' => $user->id,
                    'invoice_code' => $invoice->code,
                    'debit' => '0.00',
                    'credit' => $price,
                    'reference' => $invoice->reference,
                    'description' => $invoice->notes,
                    'document_date' => now(),
                    'due_date' => $invoice->due_date,
                    'payment_date' => null,
                    'transaction_code' => null,
                    'posted_date' => now(),
                    'type' => PaymentType::Invoice,
                ]);
            }, 3);

            return redirect()->back()->with(
                'messages',
                AlertDto::success(__('Created'))
            );

        } catch (Throwable $exception) {
            Log::error('Failed to create patient case', [
                'user_id' => auth()->id(),
                'error' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            return redirect()->back()->with(
                'messages',
                AlertDto::error(
                    $exception instanceof RuntimeException
                        ? $exception->getMessage()
                        : __('Failed to create patient case')
                )
            );
        }
    }
}
