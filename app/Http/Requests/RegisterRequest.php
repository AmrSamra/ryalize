<?php

namespace App\Http\Requests;

use App\Infrastructure\Request;

class RegisterRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'      => 'required|alpha_num|min:3',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|alpha_num|min:6'
        ];
    }
}
