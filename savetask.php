<?php
session_start();
require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if (!isset($_SESSION['userID'])) {
  $logger->log('WARN', 'Unauthorized attempt to save a task.');
  header("Location: /");
  exit();
}

$id = isset($_POST['id']) && $_POST['id'] !== "" ? intval($_POST['id']) : null;
require_once 'fw/db.php';
$connection = getConnection();

if ($id !== null) {
  $stmt = $connection->prepare("SELECT ID FROM tasks WHERE ID = ?");
  if (!$stmt) {
    $logger->log('ERROR', 'Failed to prepare statement for task lookup.', ['taskID' => $id]);
    die('Ein Fehler ist aufgetreten beim Vorbereiten des Statements.');
  }

  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $logger->log('INFO', 'No task found for the given ID during update attempt.', ['taskID' => $id]);
    $id = null;
  }

  $stmt->close();
}

require_once 'fw/header.php';
if (isset($_POST['title']) && isset($_POST['state'])) {
  $state = $_POST['state'];
  $title = $_POST['title'];
  $userID = intval($_SESSION['userID']);

  if ($id === null) {
    $logger->log('INFO', "Attempting to create a new task", ['userID' => $userID, 'title' => $title]);
    $success = executeStatement("INSERT INTO tasks (title, state, userID) VALUES (?, ?, ?)", [$title, $state, $userID]);
  } else {
    $logger->log('INFO', "Attempting to update an existing task", ['taskID' => $id, 'userID' => $userID, 'title' => $title]);
    $success = executeStatement("UPDATE tasks SET title = ?, state = ? WHERE ID = ?", [$title, $state, $id]);
  }

  if ($success) {
    $logger->log('INFO', 'Task update successful', ['taskID' => $id, 'userID' => $userID]);
    echo "<span class='info info-success'>Update successful</span>";
  } else {
    $logger->log('ERROR', 'Task update failed', ['taskID' => $id, 'userID' => $userID]);
    echo "<span class='info info-error'>Update failed</span>";
  }
} else {
  $logger->log('ERROR', "Missing title or state in task update attempt.", ['userID' => $userID]);
  echo "<span class='info info-error'>No update was made</span>";
}
require_once 'fw/footer.php';
?>