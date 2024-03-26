<?php
require_once 'config.php';
require_once 'fw/ElasticSearchLogger.php';

$logger = new ElasticSearchLogger();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset ($_GET['username']) && isset ($_GET['password'])) {
    $username = $_GET['username'];
    $password = $_GET['password'];

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die ("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_id, $db_username, $db_password);
        $stmt->fetch();

        if ($password == $db_password) {
            setcookie("username", $username, -1, "/");
            setcookie("userid", $db_id, -1, "/");
            header("Location: index.php");
            exit();
        } else {
            echo "Incorrect password";
            $logger->log("ERROR", "Incorrect password attempt for user: $username");
        }
    } else {
        echo "Username does not exist";
        $logger->log("WARN", "Login attempt for non-existent user: $username");
    }

    $stmt->close();
}
require_once 'fw/header.php';
?>

<h2>Login</h2>
<form id="form" method="get" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control size-medium" name="username" id="username">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control size-medium" name="password" id="password">
    </div>
    <div class="form-group">
        <label for="submit"></label>
        <input id="submit" type="submit" class="btn size-auto" value="Login" />
    </div>
</form>

<?php
require_once 'fw/footer.php';
?>