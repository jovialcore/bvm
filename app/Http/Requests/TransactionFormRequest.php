<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|between:-9999999999.99,9999999999.99',
            'payer' => 'required|string',
            'due_on' => 'required|date',
            'vat' => 'required|integer',
            'is_vat_inclusive' => 'required',
            'description' => 'string',



        ];
    }
}
