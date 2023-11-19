<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'vat' => $this->vat,
            'is_vat_inclusive' => $this->is_vat_inclusive ? 'Vat was added' : "No vat",
            $this->mergeWhen(Auth::user()->role == 'admin', [
                'payer' => $this->payer,
            ]),
            'transaction_type' => $this->transaction_type,
        ];
    }
}
