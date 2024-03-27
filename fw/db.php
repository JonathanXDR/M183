<?php

require_once 'ElasticSearchLogger.php';

function executeStatement($statement)
{
    $logger = new ElasticSearchLogger();
    $conn = getConnection();

    if (!$conn) {
        $logger->log('ERROR', 'Failed to obtain database connection.');
        return false;
    }

    $stmt = $conn->prepare($statement);

    if (!$stmt) {
        $logger->log('ERROR', 'Failed to prepare statement', ['statement' => $statement, 'error' => $conn->error]);
        return false;
    }

    if ($stmt->execute()) {
        $logger->log('INFO', 'Statement executed successfully', ['statement' => $statement]);
    } else {
        $logger->log('ERROR', 'Statement execution failed', ['statement' => $statement, 'error' => $stmt->error]);
    }

    $stmt->store_result();
    return $stmt;
}

function getConnection()
{
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/config.php";
    //require_once 'config.php';
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        $logger = new ElasticSearchLogger();
        $logger->log('ERROR', 'Database connection error', ['error' => $conn->connect_error]);
        die ("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>