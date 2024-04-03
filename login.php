<?php
require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'fw/db.php';
    $conn = getConnection();

    if (!$conn) {
        $logger->log('ERROR', 'Database connection failed during login attempt');
        die("Connection failed");
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=?");
    $username = $_POST['username'] ?? '';
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $password = $_POST['password'] ?? '';

        if (password_verify($password, $user['password'])) {
            setcookie("username", $username, time() + (86400 * 30), "/");
            setcookie("userID", $user['id'], time() + (86400 * 30), "/");
            $logger->log('INFO', 'Login successful', ['username' => $username]);
            header("Location: index.php");
            exit();
        } else {
            echo "Incorrect password or username";
            $logger->log('ERROR', 'Incorrect password attempt', ['username' => $username]);
        }
    } else {
        echo "Incorrect password or username";
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
        <input type="text" class="form-control size-medium" name="username" id="username" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control size-medium" name="password" id="password" required>
    </div>
    <div class="form-group">
        <input id="submit" type="submit" class="btn size-auto" value="Login" />
    </div>
</form>

<?php
require_once 'fw/footer.php';
?>