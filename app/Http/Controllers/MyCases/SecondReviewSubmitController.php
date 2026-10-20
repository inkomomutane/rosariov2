<?php

namespace App\Http\Controllers\MyCases;

use App\Data\InvoiceData;
use App\Enum\InvoiceStatus;
use App\Enum\PatientCaseStatus;
use App\Enum\PaymentType;
use App\Models\CasePricing;
use App\Models\Invoice;
use App\Models\PatientCase;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class SecondReviewSubmitController
{
    public function __invoke(PatientCase $case): JsonResponse
    {
        try {

            if($case->secondReviewInvoice){
                return response()->json(InvoiceData::fromModel($case->secondReviewInvoice), 200);
            }

            $invoice = null;
            DB::transaction(static function () use ($case,$invoice): void {
                $user = auth()->user();

                $case = PatientCase::query()
                    ->lockForUpdate()
                    ->findOrFail($case->id);

                if ($case->status !== PatientCaseStatus::REVIEWED) {
                    throw new RuntimeException(
                        __('Case is not available for second review')
                    );
                }

                $price = CasePricing::query()
                    ->whereCaseType($case->case_type)
                    ->whereReviewPhase(2)
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
                    'second_review' => true,
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

                $case->update([
                    'status' => PatientCaseStatus::SECOND_REVIEW_SUBMITTED,
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

            return response()->json(
                 $invoice ? InvoiceData::fromModel($invoice) : null
            , 200);

        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);

        } catch (Throwable $exception) {
            Log::error('Failed to submit second review', [
                'case_id' => $case->id,
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            return response()->json([
                'message' => __('Failed to submit second review'),
            ], 500);
        }
    }
}
