<?php

namespace App\Http\Controllers\Payments;

use App\Data\PaymentDataDto;
use App\Data\ProfileDto;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserBalanceController
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $user->load('payments');

        return Inertia::render('Payments/Balance', [
            'doctor' => ProfileDto::fromModel($user),
            'balance' => $user->balance,
            'pending_amount' => $user->pending_amount,
            'withdraw_amount' => $user->withdraw_amount,
            'payments' => PaymentDataDto::collect(Payment::with('user')->whereUserId($user->id)
                ->orderBy('document_date', 'desc')
                ->paginate(15))
        ]);
    }
}

