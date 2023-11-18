<?php

namespace App\Services;

use App\Http\Controllers\Api\Admin\TransactionController;
use App\Http\Requests\TransactionFormRequest;
use App\Http\Requests\updateTransactionRequest;
use App\Http\Resources\TransactionResourceCollection;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TransactionService
{


    public function createTransaction($request): bool
    {

        $transaction = new Transaction();

        $transaction->amount = $request->amount;
        $transaction->payer = $request->payer;
        $transaction->vat =  $request->vat;
        $transaction->is_vat_inclusive = $request->is_vat_inclusive;

        $transaction = $this->determineStatus($transaction, $request, $request->due_on);
        $transaction->due_on = $request->due_on;
        return $transaction->save();
    }


    public function updateTransaction($id, $request): bool
    {

        $transaction = Transaction::findorFail($id);

        $transaction->amount = $request->amount;
        $transaction->paid_on = $request->paid_on;

        $transaction = $this->determineStatus($transaction, $request, $request->paid_on);
        $transaction->paid_on = $request->paid_on;
        return $transaction->save();
    }

    private function determineStatus($transaction, $request,  $date): Transaction
    {
        $currentDate = Carbon::now();


        if ($currentDate->greaterThan($date) && $transaction->transaction_type == 'part_payment') {
            $transaction->status = 'overdue';
        }
        if ($currentDate->greaterThan($date) && $transaction->transaction_type == 'full_payment') {
            $transaction->status = 'paid';
        }


        if ($currentDate->lessThan($date) && empty($request->amount)) {
            $transaction->status = 'outstanding';
        }

        if (
            $currentDate->lessThan($date)
            &&
            ((
                empty($request->amount) || $transaction->transaction_type == 'part_payment'
            ))
        ) {
            $transaction->status = 'outstanding';
        }


        if ($currentDate->lessThan($date)) {
            $transaction->status = 'overdue';
        }
        return $transaction;
    }


    public function listTransaction()
    {

        if (Auth::user()->role == 'admin') {

            return new TransactionResourceCollection(Transaction::all());
        } else {
            return new TransactionResourceCollection(Transaction::whereHas('users')->get());
        }
    }
}
