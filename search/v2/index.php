<?php

require_once '../../fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if (!isset ($_GET["userid"]) || !isset ($_GET["terms"])) {
    $logger->log('ERROR', 'Search failed: Not enough information to search', ['userid' => $_GET['userid'] ?? 'N/A']);
    die ("Not enough information to search");
}

$userid = $_GET["userid"];
$terms = $_GET["terms"];

$logger->log('INFO', 'Search performed', ['userid' => $userid, 'terms' => $terms]);

require_once '../../fw/db.php';
$stmt = executeStatement("select ID, title, state from tasks where userID = $userid and title like '%$terms%'");
if ($stmt->num_rows > 0) {
    $stmt->bind_result($db_id, $db_title, $db_state);
    while ($stmt->fetch()) {
        echo $db_title . ' (' . $db_state . ')<br />';
    }
}
?>