<?php
require_once 'config.php';
require_once 'fw/ElasticSearchLogger.php';

$logger = new ElasticSearchLogger();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $logger->log('INFO', 'Login attempt', ['username' => $username]);

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        $logger->log('ERROR', 'Database connection failed', ['error' => $conn->connect_error]);
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($password === $user['password']) {
            setcookie("username", $username, time() + (86400 * 30), "/");
            setcookie("userid", $user['id'], time() + (86400 * 30), "/");
            $logger->log('INFO', 'Login successful', ['username' => $username]);
            header("Location: index.php");
            exit();
        } else {
            echo "Incorrect password";
            $logger->log('ERROR', 'Incorrect password attempt', ['username' => $username]);
        }
    } else {
        echo "Username does not exist";
        $logger->log('WARN', 'Login attempt for non-existent user', ['username' => $username]);
    }

    $stmt->close();
}
require_once 'fw/header.php';
?>

<h2>Login</h2>
<form id="form" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, 'UTF-8'); ?>">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control size-medium" name="username" id="username">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control size-medium" name="password" id="password">
    </div>
    <div class="form-group">
        <input id="submit" type="submit" class="btn size-auto" value="Login" />
    </div>
</form>

<?php
require_once 'fw/footer.php';
?>