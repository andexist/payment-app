<?php

namespace App\Http\Controllers\Requests;

use App\Account;
use App\Payment;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateAccountRequest
 * @package App\Http\Controllers\Requests
 */
class CreateAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clientId' => 'required|exists:clients,id',
            'accountName' => 'required',
            'iban' => 'required|max:32|unique:accounts|regex:/^[A-Z]{2}[A-Z0-9]{13,30}$/',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required:in:' . implode('currency', Account::AVAILABLE_CURRENCIES)
        ];
    }
}
