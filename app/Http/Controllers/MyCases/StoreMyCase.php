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
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class StoreMyCase
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function __invoke(PatientCaseRequestDto $dto)
     {
         $person = auth()->user()->person()->firstOrCreate([
             'user_id' => auth()->id()
         ], [
             'name' => auth()->user()->name,
             'last_name' => ''
         ]);

         $case = $person->cases()->create([
             'case_code' => 'CASE-' . \Str::ulid(),
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

         $invoice = Invoice::create([
             'code' => 'INV-' . \Str::ulid(),
             'issue_date' => now(),
             'due_date' => now()->addDays(30),
             'paid_date' => null,
             'cancelled_date' => null,
             'user_id' => auth()->id(),
             'status' => InvoiceStatus::ISSUED,
             'reference' => __('Invoice'),
             'notes' => __(''),
         ]);

         $price = CasePricing::whereCaseType($case->case_type)->whereReviewPhase(1)->first()?->price;

         if(!$price) {
             return redirect()->back()->with('messages', AlertDto::error(__('No pricing found for this case type')));
         }

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
             'due' => '0'
         ]);

         Payment::create([
             'user_id' => auth()->id(),
             'invoice_code' => $invoice->code,
             'debit' => '0.00',
             'credit' => $invoice->rows()->sum('subtotal') ?? '0.00',
             'reference' => $invoice->reference,
             'description' => $invoice->notes,
             'document_date' => now(),
             'due_date' => $invoice->due_date,
             'payment_date' => null,
             'transaction_code' => null,
             'posted_date' => now(),
             'type' => PaymentType::Invoice
         ]);

         return redirect()->back()->with('messages', AlertDto::success(__('Created')));
     }
}
