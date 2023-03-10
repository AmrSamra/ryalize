<?php

use App\Models\Transaction;

return [
    // 01_users_seeder
    'users' => array_map(function ($i) {
        return [
            'id' => $i,
            'name' => 'User ' . $i,
            'email' => 'user' . $i . '@example.com',
            'password' => bcrypt('password'),
        ];
    }, range(1, 10)),

    // 02_locations_seeder
    'locations' => array_map(function ($i) {
        return [
            'id'        => $i,
            'user_id'   => rand(1, 10),
            'name'      => 'Location ' . $i,
            'city'      => 'City ' . $i,
            'block'     => rand(111, 999),
        ];
    }, range(1, 100000)),

    // 03_transactions_seeder
    'transactions' => array_map(function ($i) {
        return [
            'id'            => $i,
            'user_id'       => rand(1, 10),
            'location_id'   => rand(1, 10),
            'type'          => Transaction::$types[rand(0, 1)],
            'amount'        => rand(100, 1000),
        ];
    }, range(1, 1000000)),
];
