<?php

require_once dirname(__DIR__) . '/config.php';
require_once 'ElasticSearchLogger.php';

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
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if ($conn->connect_error) {
        $logger->log('ERROR', 'Database connection error', ['error' => $conn->connect_error]);
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>