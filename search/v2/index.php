<?php
require_once '../../fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

$userID = isset($_GET["userID"]) ? $_GET["userID"] : null;
$terms = isset($_GET["terms"]) ? $_GET["terms"] : null;

if (!$userID || !$terms) {
    $logger->log('ERROR', 'Search failed: Not enough information to search', ['userID' => $userID, 'terms' => $terms]);
    die("Not enough information to search");
}

$terms = htmlspecialchars($terms);
$logger->log('INFO', 'Sanitized search terms', ['userID' => $userID, 'terms' => $terms]);

require_once '../../fw/db.php';
$conn = getConnection();
if (!$conn) {
    $logger->log('ERROR', 'Database connection failed during search operation.', ['userID' => $userID, 'terms' => $terms]);
    die("Database connection failed.");
}

$terms = "%{$terms}%";
if ($stmt = $conn->prepare("SELECT ID, title, state FROM tasks WHERE userID = ? AND title LIKE ?")) {
    $stmt->bind_param("is", $userID, $terms);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        echo htmlspecialchars($row['title']) . ' (' . htmlspecialchars($row['state']) . ')<br />';
        $rows[] = $row;
    }

    $logger->log('INFO', 'Search results fetched', ['userID' => $userID, 'results' => $rows]);
} else {
    $logger->log('ERROR', 'Failed to prepare SQL statement for search.', ['userID' => $userID, 'terms' => $terms]);
}
?>