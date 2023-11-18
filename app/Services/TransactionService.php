<?php

namespace App\Services;

use App\Http\Controllers\Api\Admin\TransactionController;
use App\Http\Requests\TransactionFormRequest;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionService
{


    public function createTransaction(TransactionFormRequest $request): bool
    {

        $transaction = new Transaction();
        $currentDate = Carbon::now();

        $transaction->amount = $request->amount;
        $transaction->payer = $request->payer;
        $transaction->vat =  $request->vat;
        $transaction->is_vat_inclusive = $request->is_vat_inclusive;


        if ($currentDate->greaterThan($request->due_on) && $transaction->transaction_type == 'part_payment') {
            $transaction->status = 'overdue';
        }
        if ($currentDate->greaterThan($request->due_on) && $transaction->transaction_type == 'full_payment') {
            $transaction->status = 'paid';
        }


        if ($currentDate->lessThan($request->due_on) && empty($request->amount)) {
            $transaction->due_on = $request->due_on;
            $transaction->status = 'outstanding';
        }

        if (
            $currentDate->lessThan($request->due_on)
            &&
            ((
                empty($request->amount) || $transaction->transaction_type == 'part_payment'
            ))
        ) {
            $transaction->due_on = $request->due_on;
            $transaction->status = 'outstanding';
        }


        if ($currentDate->lessThan($request->due_on)) {
            $transaction->due_on = $request->due_on;
            $transaction->status = 'overdue';
        }

        return $transaction->save();
    }
}
