<?php
if (!isset($_COOKIE['username'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$stmt = $conn->prepare("SELECT users.ID, users.username, users.password, roles.title FROM users INNER JOIN permissions ON users.ID = permissions.userID INNER JOIN roles ON permissions.roleID = roles.ID ORDER BY username");
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($db_id, $db_username, $db_password, $db_title);

$users = [];
while ($stmt->fetch()) {
    $users[] = ['id' => $db_id, 'username' => $db_username, 'password' => $db_password, 'title' => $db_title];
}

require_once '../fw/header.php';
?>

<h2>User List</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td>
                <?= htmlspecialchars($user['id']) ?>
            </td>
            <td>
                <?= htmlspecialchars($user['username']) ?>
            </td>
            <td>
                <?= htmlspecialchars($user['title']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
require_once '../fw/footer.php';
?>