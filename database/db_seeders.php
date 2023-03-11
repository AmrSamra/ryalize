<?php

use App\Models\Transaction;

$transactions = 111 * 1000;

return [
    // 01_users_seeder
    'users' => [
        [
            'name'      => 'Test User',
            'email'     => 'user@test.com',
            'password'  => bcrypt('password'),
        ],
        ...array_map(function ($i) {
            return [
                'name'      => 'Test User ' . $i,
                'email'     => 'user' . $i . '@test.com',
                'password'  => bcrypt('password'),
            ];
        }, range(1, 10))
    ],

    // 02_locations_seeder
    'locations' => array_merge(
        ...array_map(
            function ($userId) {
                $locations = [];
                for ($i = 111; $i <= 121; $i++) {
                    if (rand(0, 1) == 0) {
                        $rand = rand(1, 11);
                        $locations[] = [
                            'user_id'   => $userId,
                            'name'      => 'Location ' . $rand . ' ' . $userId,
                            'city'      => 'City' . $rand,
                            'block'     => $i,
                        ];
                    }
                }
                return $locations;
            },
            range(1, 11)
        ),
    ),


    // 03_transactions_seeder
    'transactions' => array_map(function ($i) {
        return [
            'user_id'       => rand(1, 11),
            'type'          => Transaction::$types[rand(0, 1)],
            'amount'        => (float) (rand(1000, 9999) / 10),
            'created_at'    => date('Y-m-d H:i:s', strtotime("-{$i} minute")),
            'updated_at'    => date('Y-m-d H:i:s', strtotime("-{$i} minute")),
        ];
    }, range(1, $transactions)),
];
