<?php

namespace App\Http\Controllers\Payments;

use App\Data\AlertDto;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class MakePaymentController
{
    public function __invoke(Invoice $invoice, Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:mpesa,bank,card',
            'transaction_code' => 'required|string',
            'reference' => 'nullable|string',
        ]);

        $user = auth()->user();

        if (!$user) {
            return redirect()->back()->with('messages', AlertDto::error(__('You must be logged in to make payments')));
        }

        $payment = Payment::create([
            'user_id' => $user->id,
            'invoice_code' => $invoice->code,
            'credit' => $request->input('amount'),
            'reference' => $request->input('reference', $request->input('transaction_code')),
            'description' => 'Payment for invoice ' . $invoice->code,
            'payment_date' => now(),
            'transaction_code' => $request->input('transaction_code'),
            'posted_date' => now(),
        ]);

        // Update invoice status based on total payments
        $totalPaid = Payment::where('invoice_code', $invoice->code)->sum('credit');
        $invoiceTotal = $invoice->rows()->sum('total'); // Assuming rows have a total field

        if ($totalPaid >= $invoiceTotal) {
            $invoice->update(['status' => 'paid', 'paid_date' => now()]);
        } elseif ($totalPaid > 0) {
            $invoice->update(['status' => 'partially_paid']);
        }

        return redirect()->back()->with('messages', AlertDto::success(__('Payment recorded successfully')));
    }
}

