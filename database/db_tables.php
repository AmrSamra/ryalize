<?php

return [
    // 01_users_table
    'users' => [
        'id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
        'name VARCHAR(255) NOT NULL',
        'email VARCHAR(255) NOT NULL UNIQUE',
        'password VARCHAR(255) NOT NULL',
        // Timestamps
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        // Indexes
        'INDEX email_index (email)'
    ],

    // 02_auth_tokens_table
    'auth_tokens' => [
        'id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
        'user_id INT(11) UNSIGNED NOT NULL',
        'token VARCHAR(255) NOT NULL UNIQUE',
        'expires_at TIMESTAMP NOT NULL',
        // Timestamps
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        // Indexes
        'INDEX token_index (token)',
        'INDEX expires_at_index (expires_at)',
        // Foreign Keys Constraints
        'CONSTRAINT fk_auth_tokens_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE'
    ],

    // 03_locations_table
    'locations' => [
        'id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
        'user_id INT(11) UNSIGNED NOT NULL',
        'name VARCHAR(255) NOT NULL',
        'city VARCHAR(255) NOT NULL',
        'block INT(3) NOT NULL',
        // Timestamps
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        // Indexes
        'INDEX name_index (name)',
        'INDEX city_index (city)',
        'INDEX block_index (block)',
        // Foreign Keys Constraints
        'CONSTRAINT fk_locations_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE'
    ],

    // 04_transactions_table
    'transactions' => [
        'id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
        'user_id INT(11) UNSIGNED NOT NULL',
        'type VARCHAR(255) NOT NULL',
        'amount FLOAT(8,3) NOT NULL',
        // Timestamps
        'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        // Indexes
        'INDEX type_index (type)',
        // Foreign Keys Constraints
        'CONSTRAINT fk_transactions_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE',
    ]
];
