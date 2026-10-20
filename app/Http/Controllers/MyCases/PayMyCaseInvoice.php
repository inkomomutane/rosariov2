<?php

namespace App\Http\Controllers\MyCases;

use App\Data\AlertDto;
use App\Data\InvoiceData;
use App\Enum\InvoiceStatus;
use App\Enum\PatientCaseStatus;
use App\Enum\PaymentType;
use App\Models\Invoice;
use App\Models\PatientCase;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Samuelbie\Mpesa\Transaction;
use Throwable;

class PayMyCaseInvoice
{
    public function __invoke(
        PatientCase $case,
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'contact' => [
                'required',
                'string',
                'regex:/^8[45]\d{7}$/',
            ],
            'invoice_id' => [
                'required',
                'exists:invoices,id',
            ],
        ]);

        try {
            $this->makeInvoicePayment(
                $case,
                $validated['contact'],
                $validated['invoice_id']
            );

            return redirect()->back()->with(
                'messages',
                AlertDto::success(__('Payment completed successfully'))
            );

        } catch (Throwable $exception) {
            Log::error('Failed to process invoice payment', [
                'case_id' => $case->id,
                'invoice_id' => $validated['invoice_id'],
                'error' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            return redirect()->back()->with(
                'messages',
                AlertDto::error(
                    $exception instanceof RuntimeException
                        ? $exception->getMessage()
                        : __('Failed to process payment')
                )
            );
        }
    }

    /**
     * @throws Throwable
     */
    private function makeInvoicePayment(
        PatientCase $case,
        string $contact,
        string $invoiceId
    ): void {
        $invoice = Invoice::with(['rows', 'patientCase'])
            ->where('patient_case_id', $case->id)
            ->where('user_id', auth()->id())
            ->findOrFail($invoiceId);

        if ($invoice->status !== InvoiceStatus::ISSUED) {
            throw new RuntimeException(__('Invoice is not payable'));
        }

        $invoiceData = InvoiceData::fromModel($invoice);

        $response = (new Transaction())->c2b(
            $invoiceData->total,
            $contact,
            'Pagamento',
            strtolower((string) Str::ulid())
        );

        if (
            (int) $response->getStatusCode() !== 200 ||
            $response->getCode() !== 'INS-0'
        ) {
            throw new RuntimeException(
                __('Payment failed') . ': ' . $response->getDescription()
            );
        }

        DB::transaction(static function () use ($invoice, $invoiceData, $response): void {
            $date = now();
            $transactionId = $response->getTransactionId();

            $invoice->update([
                'paid_date' => $date,
                'status' => InvoiceStatus::PAID,
                'notes' => $invoice->notes .
                    "\n - Pago via M-Pesa on " . $date->format('Y-m-d') .
                    "\n - Transaction ID: " . $transactionId,
            ]);

            $invoice->patientCase->update([
                'status' => $invoice->second_review ? PatientCaseStatus::SECOND_REVIEW_PAID: PatientCaseStatus::PAID,
            ]);

            Payment::create([
                'user_id' => $invoice->user_id,
                'invoice_code' => $invoice->code,
                'debit' => $invoiceData->total,
                'credit' => '0.00',
                'reference' => 'REF. ' . $invoice->code,
                'description' => 'Pagamento via M-Pesa da factura ' .
                    $invoice->code .
                    "\n - Transaction ID: " . $transactionId,
                'document_date' => $date,
                'due_date' => $invoice->due_date,
                'payment_date' => $date,
                'transaction_code' => $transactionId,
                'posted_date' => $date,
                'type' => PaymentType::Invoice,
            ]);
        }, 3);
    }
}
