<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TransactionsRequest;
use App\Models\Location;
use App\Models\Transaction;
use Psr\Http\Message\RequestInterface as IRequest;
use Psr\Http\Message\ResponseInterface as IResponse;

class TransactionsController extends ApiController
{
    public function index(IRequest $request, IResponse $response, ...$args): IResponse
    {
        $request = new TransactionsRequest($request);

        $params = $request->only('city', 'block', 'type', 'limit', 'page');

        $transactional = [['transactions.user_id', '=', $this->user->id]];
        $locational  = [];

        if ($params['city']) {
            $locational[] = ['locations.city', '=', (string) $params['city']];
        }
        if ($params['block']) {
            $locational[] = ['locations.block', '=', (int) $params['block']];
        }
        if ($params['type']) {
            $transactional[] = ['transactions.type', '=', $params['type']];
        }

        $limit  = (int) ($params['limit'] ?? 20);
        $page   = (int) ($params['page'] ?? 1);
        $offset = (int) ($limit * ($page - 1));

        $transactions = Transaction::whereHasThrough(Location::class, 'user_id', $locational)
            ->where(...$transactional);

        $total = (clone $transactions)->count();

        $transactions = (clone $transactions)->orderBy(['created_at'], 'DESC')
            ->limit($limit)
            ->offset($offset);

        $pages = ceil($total / $limit);

        $metadata = [
            'total' => $total,
            'limit' => $limit,
            'page'  => $page,
            'next'  => $page < $pages ? $page + 1 : null,
            'prev'  => $page > 1 ? $page - 1 : null,
            'pages' => $pages,
        ];

        return json($response, 'Transactions Retrieved', $transactions->get(), $metadata);
    }
}
