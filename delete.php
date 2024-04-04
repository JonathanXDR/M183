<?php
session_start();
require_once 'fw/db.php';
require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if (!isset($_SESSION['userID'])) {
    $logger->log('WARN', 'Unauthorized attempt to access delete.php.');
    header("Location: login.php");
    exit();
}

$userID = intval($_SESSION['userID']);
$taskId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$logger->log('INFO', 'Delete operation initiated.', ['userID' => $userID, 'taskID' => $taskId]);

$conn = getConnection();
if (!$conn) {
    $logger->log('ERROR', 'Database connection failed during task deletion.');
    die("Datenbankverbindung fehlgeschlagen.");
}

$stmt = $conn->prepare("SELECT ID FROM tasks WHERE ID = ? AND UserID = ?");
$stmt->bind_param("ii", $taskId, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $logger->log('ERROR', 'Attempt to delete a non-existent or unauthorized task.', ['userID' => $userID, 'taskID' => $taskId]);
    die("Aufgabe existiert nicht oder Sie sind nicht berechtigt, diese Aktion durchzuführen.");
}

if ($stmt = $conn->prepare("DELETE FROM tasks WHERE ID = ?")) {
    $stmt->bind_param("i", $taskId);
    if ($stmt->execute()) {
        $logger->log('INFO', 'Task successfully deleted.', ['userID' => $userID, 'taskID' => $taskId]);
        header("Location: index.php");
        exit();

    } else {
        $logger->log('ERROR', 'Failed to delete task.', ['userID' => $userID, 'taskID' => $taskId, 'error' => $stmt->error]);
        die("Fehler beim Löschen des Tasks.");
    }
} else {
    $logger->log('ERROR', 'Failed to prepare statement for task deletion.', ['userID' => $userID, 'error' => $conn->error]);
    die("Ein Fehler ist aufgetreten beim Vorbereiten der Löschoperation.");
}
?>