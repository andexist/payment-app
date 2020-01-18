<?php

namespace App\Http\Controllers\Requests\PaymentRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ProcessPaymentRequest
 * @package App\Http\Controllers\Requests
 */
class ProcessPaymentRequest extends FormRequest
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
            'code' => 'required|in:111'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.in' => 'Invalid verification code.',
        ];
    }
}
