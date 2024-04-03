<?php
require_once 'fw/db.php';
$conn = getConnection();
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

$userid = isset($_COOKIE['userid']) ? intval($_COOKIE['userid']) : 0;
$stmt = $conn->prepare("SELECT ID, title, state FROM tasks WHERE UserID = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->store_result();
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
                    <?= htmlspecialchars($db_id) ?>
                </td>
                <td class="wide">
                    <?= htmlspecialchars($db_title) ?>
                </td>
                <td>
                    <?= htmlspecialchars(ucfirst($db_state)) ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($db_id) ?>">edit</a> | <a
                        href="delete.php?id=<?= htmlspecialchars($db_id) ?>">delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</section>