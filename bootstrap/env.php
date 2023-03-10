<?php

// Load environment variables

use Dotenv\Dotenv;

$environment = Dotenv::createImmutable(base_path());
$environment->load();
