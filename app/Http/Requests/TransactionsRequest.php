<?php

namespace App\Http\Requests;

use App\Infrastructure\Request;
use App\Models\Transaction;

class TransactionsRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'city'  => 'nullable|alpha_num',
            'block' => 'nullable|integer',
            'type'  => 'nullable|in:' . implode(',', Transaction::$types),
            'limit' => 'nullable|integer',
            'page'  => 'nullable|integer'
        ];
    }
}
