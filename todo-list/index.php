<?php

// Check if the user is logged in
if (!isset($_COOKIE['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
<h2>Welcome, <?php echo $_COOKIE['username']; ?>!</h2>
</body>
</html>