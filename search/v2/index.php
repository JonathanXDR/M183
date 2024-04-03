<?php
require_once '../../fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if (!isset($_GET["userid"]) || !isset($_GET["terms"])) {
    $logger->log('ERROR', 'Search failed: Not enough information to search', ['userid' => $_GET['userid'] ?? 'N/A']);
    die("Not enough information to search");
}

$userid = intval($_GET["userid"]);
$terms = htmlspecialchars($_GET["terms"]);

$logger->log('INFO', 'Search performed', ['userid' => $userid, 'terms' => $terms]);

require_once '../../fw/db.php';
$conn = getConnection();
if ($conn) {
    $terms = "%{$terms}%";
    if ($stmt = $conn->prepare("SELECT ID, title, state FROM tasks WHERE userID = ? AND title LIKE ?")) {
        $stmt->bind_param("is", $userid, $terms);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo htmlspecialchars($row['title']) . ' (' . htmlspecialchars($row['state']) . ')<br />';
        }
    }
}
?>