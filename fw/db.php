<?php

$basePath = dirname(__DIR__, 1);
require_once 'ElasticSearchLogger.php';
require_once $basePath . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($basePath);
$dotenv->load();

function executeStatement($statement, $params = [])
{
    $logger = new ElasticSearchLogger();
    $conn = getConnection();

    if (!$conn) {
        $logger->log('ERROR', 'Failed to obtain database connection.');
        return false;
    }

    if ($stmt = $conn->prepare($statement)) {
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        if ($stmt->execute()) {
            $logger->log('INFO', 'Statement executed successfully', ['statement' => $statement]);
            $stmt->store_result();
            return $stmt;
        } else {
            $logger->log('ERROR', 'Statement execution failed', ['statement' => $statement, 'error' => $stmt->error]);
            return false;
        }
    } else {
        $logger->log('ERROR', 'Failed to prepare statement', ['statement' => $statement, 'error' => $conn->error]);
        return false;
    }
}

function getConnection()
{
    $logger = new ElasticSearchLogger();
    $conn = new mysqli($_ENV['DATABASE_HOST'], $_ENV['DATABASE_USER'], $_ENV['DATABASE_PASSWORD'], $_ENV['DATABASE_NAME'], $_ENV['DATABASE_PORT']);

    if ($conn->connect_error) {
        $logger->log('ERROR', 'Database connection error', ['error' => $conn->connect_error]);
        return null;
    }

    return $conn;
}
?>