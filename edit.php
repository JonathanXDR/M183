<?php
if (!isset($_COOKIE['userid'])) {
  header("Location: /");
  exit();
}

require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

$options = ["Open", "In Progress", "Done"];
$title = "";
$state = "";
$taskid = "";

if (isset($_GET['id'])) {
  $taskid = $_GET["id"];
  require_once 'fw/db.php';
  $stmt = executeStatement("SELECT ID, title, state FROM tasks WHERE ID = ?", [$taskid]);

  $logger->log('INFO', 'Fetching task for editing', ['task_id' => $taskid]);

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($db_id, $db_title, $db_state);
    $stmt->fetch();
    $title = htmlspecialchars($db_title, ENT_QUOTES, 'UTF-8');
    $state = $db_state;
  }
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
    <input type="text" class="form-control size-medium" name="title" id="title" value="<?= $title ?>">
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
<script>
  $(document).ready(function () {
    $('#form').validate({
      rules: {
        title: {
          required: true
        }
      },
      messages: {
        title: 'Please enter a description.',
      },
      submitHandler: function (form) {
        form.submit();
      }
    });
  });
</script>

<?php
require_once 'fw/footer.php';
?>