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

    $stmt = $conn->prepare($statement);
    if (!$stmt) {
        $logger->log('ERROR', 'Failed to prepare statement', ['statement' => $statement, 'error' => $conn->error]);
        return false;
    }

    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_double($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        // Log or output the error
        $logger->log('ERROR', "SQL Error: " . $stmt->error, ['statement' => $statement]);
        // Displaying error (for debugging only, remove in production)
        echo "SQL Error: " . $stmt->error;
    }


    $logger->log('INFO', 'Statement executed successfully', ['statement' => $statement]);

    if (preg_match('/^(INSERT|UPDATE|DELETE)/i', $statement)) {
        $stmt->close();
        return true;
    }
    return $stmt;
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