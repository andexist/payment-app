<?php

namespace App\Http\Controllers\Requests\AccountRequest;

use App\Account;
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
            'iban' => 'required|max:32|unique:accounts|regex:/^[A-Z]{2}[A-Z0-9]{9,30}$/',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:' . implode(",", Account::AVAILABLE_CURRENCIES)
        ];
    }
}
