<?php

namespace App\Http\Controllers\Payments;

use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserBalanceController
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $doctor = $user->person;

        // Calculate balance from payments and invoices
        $totalCredit = Payment::where('user_id', $user->id)->sum('credit');
        $totalDebit = Payment::where('user_id', $user->id)->sum('debit');
        $balance = $totalCredit - $totalDebit;

        return Inertia::render('Payments/Balance', [
            'doctor' => $doctor,
            'balance' => $balance,
            'total_credit' => $totalCredit,
            'total_debit' => $totalDebit,
            'payments' => Payment::where('user_id', $user->id)
                ->orderBy('payment_date', 'desc')
                ->paginate(15)
        ]);
    }
}

