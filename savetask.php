<?php
require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();
if (!isset($_COOKIE['userid'])) {
  $logger->log('WARN', 'Unauthorized attempt to save a task.');
  header("Location: /");
  exit();
}
$id = "";
include 'fw/db.php';
if (isset($_POST['id']) && strlen($_POST['id']) != 0) {
  $id = $_POST["id"];
  $stmt = executeStatement("SELECT ID, title, state FROM tasks WHERE ID = ?", [$id]);
  if ($stmt->num_rows == 0) {
    $id = "";
  }
}
require_once 'fw/header.php';
if (isset($_POST['title']) && isset($_POST['state'])) {
  $state = $_POST['state'];
  $title = $_POST['title'];
  $userid = $_COOKIE['userid'];
  if ($id == "") {
    $logger->log('INFO', "New task created by user $userid: $title");
    $stmt = executeStatement("INSERT INTO tasks (title, state, userID) VALUES (?, ?, ?)", [$title, $state, $userid]);
  } else {
    $logger->log('INFO', "Task $id updated by user $userid.");
    $stmt = executeStatement("UPDATE tasks SET title = ?, state = ? WHERE ID = ?", [$title, $state, $id]);
  }
  echo "<span class='info info-success'>Update successful</span>";
} else {
  $logger->log('ERROR', "Task update failed by user $userid: Missing title or state.");
  echo "<span class='info info-error'>No update was made</span>";
}
require_once 'fw/footer.php';
?>