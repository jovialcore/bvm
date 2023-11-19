<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionFormRequest;
use App\Services\TransactionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    use ApiResponse;

    public function __construct(
        private TransactionService $transactionService
    ) {
    }

    public function createTransaction(TransactionFormRequest  $request)
    {
        if ($this->transactionService->createTransaction($request)) {
            return response()->json($this->success([], 'Transaction Created successfully'));
        } else {
            return response()->json([
                'response' =>  false,
                'status' => 500,
                'message' => 'Transaction Could not be created.',
            ]);
        }
    }


    public function updateTransaction($id, TransactionFormRequest  $request)
    {
        if ($this->transactionService->updateTransaction($id, $request)) {
            return response()->json($this->success([], 'Transaction Updated successfully'));
        } else {
            return response()->json([
                'response' =>  false,
                'status' => 500,
                'message' => 'Transaction Could not be upodated.',
            ]);
        }
    }

    public function  listTransaction()
    {

        $data = $this->transactionService->listTransaction();
        if ($data->count() > 0) {
            return $this->success($data, 'Transactions returned successfully');
        } else {
            return response()->json([
                'response' =>  false,
                'status' => 404,
                'message' => 'No transaction found.',
            ]);
        }
    }

    public function monthlyReport(Request $request)
    {

        
        $data = $this->transactionService->monthlyReport($request);
        if ($data->count() > 0) {
            return response()->json($this->success($data, 'Report generated successfully'));
        } else {
            return response()->json([
                'response' =>  false,
                'status' => 404,
                'message' => 'No transaction found.',
            ]);
        }
    }
}
