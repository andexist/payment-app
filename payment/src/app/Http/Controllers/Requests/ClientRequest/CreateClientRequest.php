<?php

namespace App\Http\Controllers\Requests\ClientRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateClientRequest
 * @package App\Http\Controllers\Requests
 */
class CreateClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:clients|max:50',
            'firstName' => 'required|max:50',
            'lastName' => 'required|max:50',
        ];
    }
}
