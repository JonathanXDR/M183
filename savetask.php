<?php
require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();
if (!isset($_COOKIE['userid'])) {
  $logger->log('WARN', 'Unauthorized attempt to save a task.');
  header("Location: /");
  exit();
}

$id = isset($_POST['id']) && $_POST['id'] !== "" ? intval($_POST['id']) : null;
require_once 'fw/db.php';

if ($id !== null) {
  $result = executeStatement("SELECT ID FROM tasks WHERE ID = ?", [$id]);

  if ($result === false || $result->num_rows == 0) {
    $id = null;
  }
}

require_once 'fw/header.php';
if (isset($_POST['title']) && isset($_POST['state'])) {
  $state = $_POST['state'];
  $title = $_POST['title'];
  $userid = intval($_COOKIE['userid']);

  if ($id === null) {
    $logger->log('INFO', "New task created by user $userid: $title");
    $success = executeStatement("INSERT INTO tasks (title, state, userID) VALUES (?, ?, ?)", [$title, $state, $userid]);
  } else {
    $logger->log('INFO', "Task $id updated by user $userid.");
    $success = executeStatement("UPDATE tasks SET title = ?, state = ? WHERE ID = ?", [$title, $state, $id]);
  }

  if ($success) {
    echo "<span class='info info-success'>Update successful</span>";
  } else {
    echo "<span class='info info-error'>Update failed</span>";
  }
} else {
  $logger->log('ERROR', "Task update failed by user $userid: Missing title or state.");
  echo "<span class='info info-error'>No update was made</span>";
}
require_once 'fw/footer.php';
?>