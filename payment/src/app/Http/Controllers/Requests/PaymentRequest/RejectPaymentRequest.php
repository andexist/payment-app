<?php

namespace App\Http\Controllers\Requests\PaymentRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RejectPaymentRequest
 * @package App\Http\Controllers\Requests\PaymentRequest
 */
class RejectPaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'paymentId' => 'exists:payments,id|required',
        ];
    }
}
