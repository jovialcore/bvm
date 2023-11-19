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
use Illuminate\Support\Facades\DB;

class TransactionService
{


    public function createTransaction($request): bool
    {

        $transaction = new Transaction();

        $transaction->amount = $request->amount;
        $transaction->payer = $request->payer;
        $transaction->vat =  $request->vat;
        $transaction->is_vat_inclusive = $request->is_vat_inclusive;
        $transaction->user_id = $request->user_id;

        $transaction = $this->determineStatus($transaction, $request, $request->due_on);
        $transaction->due_on = $request->due_on;
        $transaction->transaction_type = $request->transaction_type;
        // dd($transaction);
        return $transaction->save();
    }


    public function updateTransaction($id, $request): bool
    {


        $transaction = Transaction::findOrFail($id);
      

        $transaction->amount = $request->amount;
        $transaction->paid_on = $request->paid_on;
        $transaction = $this->determineStatus($transaction, $request, $request->paid_on);
        $transaction->paid_on = $request->paid_on;
        $transaction->transaction_type = $request->transaction_type;
        // dd($transaction);
        return $transaction->save();
    }

    private function determineStatus($transaction, $request,  $date): Transaction
    {
        $currentDate = Carbon::now();


        if ($currentDate->greaterThan($date) && $request->transaction_type == 'part_payment') {
            $transaction->status = 'overdue';
        }
        if ($currentDate->greaterThan($date) && $request->transaction_type == 'full_payment') {
            $transaction->status = 'paid';
        }


        if ($currentDate->lessThan($date) && empty($request->amount)) {
            $transaction->status = 'outstanding';
        }

        if (
            $currentDate->lessThan($date)
            &&
            ((
                empty($request->amount) || $request->transaction_type == 'part_payment'
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

    public function monthlyReport($request)
    {

        $request->validate(
            [
                'start_date' => 'required|date',
                'end_date' => 'required|date'
            ]
        );

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $generateReport = DB::table('transactions')
            ->selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            SUM(CASE WHEN status = "paid" THEN amount ELSE 0 END) as paid_amount,
            SUM(CASE WHEN status = "outstanding" AND due_date >= NOW() THEN amount ELSE 0 END)  as outstanding_amount,
            SUM(CASE WHEN status = "overdue" THEN amount ELSE 0 END) as overdue_amount
        ')
            ->whereBetween('created_at', [
                DB::raw("STR_TO_DATE('$startDate', '%c/%e/%Y %H:%i:%s')"),
                DB::raw("STR_TO_DATE('$endDate', '%c/%e/%Y %H:%i:%s')"),
            ])
            ->groupBy('year', 'month')
            ->orderBy('month', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return  $generateReport;
    }
}
