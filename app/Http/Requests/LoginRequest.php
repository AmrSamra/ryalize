<?php

namespace App\Http\Requests;

use App\Infrastructure\Request;

class LoginRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required|alpha_num'
        ];
    }
}
