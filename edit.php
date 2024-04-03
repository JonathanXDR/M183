<?php
require_once 'fw/db.php';
$conn = getConnection();
if (!$conn) {
  die("Connection failed: " . $conn->connect_error);
}

$options = ["Open", "In Progress", "Done"];
$title = "";
$state = "";
$taskid = "";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $taskid = intval($_GET["id"]);
  $stmt = $conn->prepare("SELECT ID, title, state FROM tasks WHERE ID = ?");
  $stmt->bind_param("i", $taskid);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
    $state = $row['state'];
  }
  $stmt->close();
}

require_once 'fw/header.php';
?>

<h1>
  <?= isset($_GET['id']) ? "Edit Task" : "Create Task" ?>
</h1>

<form id="form" method="post" action="savetask.php">
  <input type="hidden" name="id" value="<?= htmlspecialchars($taskid, ENT_QUOTES, 'UTF-8') ?>" />
  <div class="form-group">
    <label for="title">Description</label>
    <input type="text" class="form-control size-medium" name="title" id="title" value="<?= $title ?>" required>
  </div>
  <div class="form-group">
    <label for="state">State</label>
    <select name="state" id="state" class="size-auto">
      <?php foreach ($options as $option): ?>
        <option value="<?= strtolower($option); ?>" <?= $state == strtolower($option) ? 'selected' : '' ?>>
          <?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
    <input id="submit" type="submit" class="btn size-auto" value="Submit" />
  </div>
</form>

<?php
require_once 'fw/footer.php';
?>