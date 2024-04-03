<?php

$basePath = dirname(__DIR__, 1);
require_once $basePath . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($basePath);
$dotenv->load();

if (!isset($_COOKIE['username'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../fw/db.php';
$conn = getConnection();
if (!$conn) {
    die("Datenbankverbindung fehlgeschlagen");
}

$userid = isset($_COOKIE['userid']) ? intval($_COOKIE['userid']) : 0;
$stmt = $conn->prepare("SELECT roleID FROM permissions WHERE userID = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    die("Zugriff verweigert");
}

$stmt->bind_result($roleID);
$isAdmin = false;
while ($stmt->fetch()) {
    if ($roleID == 1) {
        $isAdmin = true;
        break;
    }
}
if (!$isAdmin) {
    die("Zugriff verweigert");
}

$stmt = $conn->prepare("SELECT users.ID, users.username, roles.title FROM users INNER JOIN permissions ON users.ID = permissions.userID INNER JOIN roles ON permissions.roleID = roles.ID ORDER BY username");
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = ['id' => $row['ID'], 'username' => $row['username'], 'title' => $row['title']];
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