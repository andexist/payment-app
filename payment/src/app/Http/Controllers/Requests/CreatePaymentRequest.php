<?php

namespace App\Http\Controllers\Requests;

use App\Payment;
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
            'paymentProvider' => 'required|in:' . implode(",", Payment::PAYMENT_PROVIDER),
            'accountId' => 'required|exists:accounts,id',
            'amount' => 'required',
            'receiverAccount' => 'required',
            'receiverName' => 'required',
            'details' => 'required',
        ];
    }
}