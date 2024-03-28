<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database credentials
define('DB_HOST', $_ENV['DATABASE_HOST']);
define('DB_USER', $_ENV['DATABASE_USER']);
define('DB_PASS', $_ENV['DATABASE_PASSWORD']);
define('DB_NAME', $_ENV['DATABASE_NAME']);

?>