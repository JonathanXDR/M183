<?php

$basePath = dirname(__DIR__, 1);
require_once 'ElasticSearchLogger.php';
require_once $basePath . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($basePath);
$dotenv->load();

$connection = null;

function executeStatement($statement, $params = [])
{
    global $connection;

    $logger = new ElasticSearchLogger();
    if (!$connection) {
        $connection = getConnection();
    }

    if (!$connection) {
        $logger->log('ERROR', 'Failed to obtain database connection.');
        die('Datenbankverbindung fehlgeschlagen.');
    }

    $stmt = $connection->prepare($statement);
    if ($stmt === false) {
        $logger->log('ERROR', 'Failed to prepare statement', ['statement' => $statement, 'error' => $connection->error]);
        die('Ein Fehler ist aufgetreten.');
    }

    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        if (!$stmt->bind_param($types, ...$params)) {
            $logger->log('ERROR', 'Binding parameters failed', ['statement' => $statement]);
            die('Ein Fehler ist aufgetreten.');
        }
    }

    if ($stmt->execute()) {
        $logger->log('INFO', 'Statement executed successfully', ['statement' => $statement]);
        return $stmt;
    } else {
        $logger->log('ERROR', 'Statement execution failed', ['statement' => $statement, 'error' => $stmt->error]);
        die('Ein Fehler ist aufgetreten.');
    }
}

function getConnection()
{
    global $_ENV;
    $logger = new ElasticSearchLogger();
    try {
        $conn = new mysqli($_ENV['DATABASE_HOST'], $_ENV['DATABASE_USER'], $_ENV['DATABASE_PASSWORD'], $_ENV['DATABASE_NAME'], $_ENV['DATABASE_PORT']);
        if ($conn->connect_error) {
            $logger->log('ERROR', 'Database connection error', ['error' => $conn->connect_error]);
            die('Datenbankverbindung fehlgeschlagen.');
        }
        return $conn;
    } catch (Exception $e) {
        $logger->log('ERROR', 'Database connection error', ['error' => $e->getMessage()]);
        die('Datenbankverbindung fehlgeschlagen.');
    }
}

?>