<?php

namespace App\Http\Requests;

use App\Infrastructure\Request;

class UpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $user = $this->parent->getAttribute('user');

        return [
            'name'      => 'required|alpha_num',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'required|alpha_num|min:6'
        ];
    }
}
