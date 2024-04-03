<?php
require_once '../../fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

$userid = isset($_POST["userid"]) ? $_POST["userid"] : null;
$terms = isset($_POST["terms"]) ? $_POST["terms"] : null;

if (!$userid || !$terms) {
    $logger->log('ERROR', 'Search failed: Not enough information to search', ['userid' => $userid, 'terms' => $terms]);
    die("Not enough information to search");
}

$terms = htmlspecialchars($terms);
$logger->log('INFO', 'Sanitized search terms', ['userid' => $userid, 'terms' => $terms]);

require_once '../../fw/db.php';
$conn = getConnection();
if (!$conn) {
    $logger->log('ERROR', 'Database connection failed during search operation.', ['userid' => $userid, 'terms' => $terms]);
    die("Database connection failed.");
}

$terms = "%{$terms}%";
if ($stmt = $conn->prepare("SELECT ID, title, state FROM tasks WHERE userID = ? AND title LIKE ?")) {
    $stmt->bind_param("is", $userid, $terms);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        echo htmlspecialchars($row['title']) . ' (' . htmlspecialchars($row['state']) . ')<br />';
        $rows[] = $row;
    }

    $logger->log('INFO', 'Search results fetched', ['userid' => $userid, 'results' => $rows]);
} else {
    $logger->log('ERROR', 'Failed to prepare SQL statement for search.', ['userid' => $userid, 'terms' => $terms]);
}
?>