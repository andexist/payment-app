<?php

namespace App\Http\Controllers\Requests\PaymentRequest;

use App\Account;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreatePaymentRequest
 * @package Http\Controllers\Requests
 */
class CreatePaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'accountId' => 'required|exists:accounts,id',
            'currency' => 'required|in:' . implode(",", Account::AVAILABLE_CURRENCIES),
            'amount' => 'required',
            'receiverAccount' => 'required',
            'receiverName' => 'required',
            'details' => 'required',
        ];
    }
}
