<?php
require_once 'db.php';
require_once 'ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

$conn = getConnection();
if ($conn && isset($_COOKIE['userID'])) {
    $userID = intval($_COOKIE['userID']);
    if ($stmt = $conn->prepare("SELECT users.id, roles.id, roles.title FROM users INNER JOIN permissions ON users.id = permissions.userID INNER JOIN roles ON permissions.roleID = roles.id WHERE users.id = ?")) {
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_userID, $db_roleid, $db_rolename);
            while ($stmt->fetch()) {
                $roleid = $db_roleid;
            }
        }
        $stmt->close();
    }
    $logger->log('INFO', 'User accessed page with role', ['userID' => $userID, 'roleID' => $roleid ?? 'unknown']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TBZ 'Secure' App</title>
    <link rel="stylesheet" href="/fw/style.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
</head>

<body>
    <header>
        <div>This is the insecure m183 test app</div>
        <?php if (isset($_COOKIE['userID'])) { ?>
            <nav>
                <ul>
                    <li><a href="/">Tasks</a></li>
                    <?php if (isset($roleid) && $roleid == 1) { ?>
                        <li><a href="/admin/users.php">User List</a></li>
                    <?php } ?>
                    <li><a href="/logout.php">Logout</a></li>
                </ul>
            </nav>
        <?php } ?>
    </header>
    <main>