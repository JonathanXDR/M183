<?php
require_once 'config.php';
require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if (!isset ($_COOKIE['username'])) {
    $logger->log('WARN', 'Unauthorized task list access attempt', ['cookie' => $_COOKIE]);
    header("Location: ../login.php");
    exit();
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$userid = $_COOKIE['userid'];

$logger->log('INFO', 'Task list viewed', ['userid' => $userid]);

// Check connection
if ($conn->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement to retrieve user from database
$stmt = $conn->prepare("select ID, title, state from tasks where UserID = $userid");
// Execute the statement
$stmt->execute();
// Store the result
$stmt->store_result();
// Bind the result variables
$stmt->bind_result($db_id, $db_title, $db_state);
?>
<section id="list">
    <a href="edit.php">Create Task</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>State</th>
            <th></th>
        </tr>
        <?php while ($stmt->fetch()) { ?>
            <tr>
                <td>
                    <?php echo $db_id ?>
                </td>
                <td class="wide">
                    <?php echo $db_title ?>
                </td>
                <td>
                    <?php echo ucfirst($db_state) ?>
                </td>
                <td>
                    <a href="edit.php?id=<?php echo $db_id ?>">edit</a> | <a
                        href="delete.php?id=<?php echo $db_id ?>">delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</section>