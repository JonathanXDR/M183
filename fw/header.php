<?php
$id = 0;
$roleid = 0;
require_once 'db.php';
$conn = getConnection();
if (isset($_COOKIE['userid'])) {
    $userid = intval($_COOKIE['userid']);
    if ($stmt = $conn->prepare("SELECT users.id, roles.id, roles.title FROM users INNER JOIN permissions ON users.id = permissions.userid INNER JOIN roles ON permissions.roleID = roles.id WHERE users.id = ?")) {
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_userid, $db_roleid, $db_rolename);
            $stmt->fetch();
            $roleid = $db_roleid;
        }
        $stmt->close();
    }
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
        <?php if (isset($_COOKIE['userid'])) { ?>
            <nav>
                <ul>
                    <li><a href="/">Tasks</a></li>
                    <?php if ($roleid == 1) { ?>
                        <li><a href="/admin/users.php">User List</a></li>
                    <?php } ?>
                    <li><a href="/logout.php">Logout</a></li>
                </ul>
            </nav>
        <?php } ?>
    </header>
    <main>