<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionFormRequest;
use App\Services\TransactionService;
use App\Traits\ApiResponse;

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
            return $this->success([], 'Transaction Created successfully');
        } else {
            return $this->error([], 'Transaction Could not be created. Something happened');
        }
    }
}
