<?php

$basePath = dirname(__DIR__, 1);
require_once $basePath . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($basePath);
$dotenv->load();

if (!isset($_COOKIE['username'])) {
    header("Location: ../login.php");
    exit();
}

$conn = new mysqli($_ENV['DATABASE_HOST'], $_ENV['DATABASE_USER'], $_ENV['DATABASE_PASSWORD'], $_ENV['DATABASE_NAME'], $_ENV['DATABASE_PORT']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$stmt = $conn->prepare("SELECT users.ID, users.username, roles.title FROM users INNER JOIN permissions ON users.ID = permissions.userID INNER JOIN roles ON permissions.roleID = roles.ID ORDER BY username");
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($db_id, $db_username, $db_title);

$users = [];
while ($stmt->fetch()) {
    $users[] = ['id' => $db_id, 'username' => $db_username, 'title' => $db_title];
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