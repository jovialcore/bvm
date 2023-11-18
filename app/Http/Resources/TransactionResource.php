<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'transaction_ref' => $this->id,
            'amount' => $this->amount,
            'due_on' => $this->due_on,
            'paid_on' => $this->paid_on,
            'transaction_type' => $this->transaction_type,
        ];
    }
}
