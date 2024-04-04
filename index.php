<?php
session_start();
require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if (!isset($_SESSION['username'])) {
    $logger->log('WARN', 'Unauthorized access attempt to index.php');
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$logger->log('INFO', 'User accessed the index page.', ['username' => $username]);

require_once 'fw/header.php';
?>
<h2>Welcome,
    <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>!
</h2>

<?php
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $logger->log('INFO', 'Displaying tasks and search for user.', ['username' => $username, 'userID' => $userID]);
    require_once 'user/tasklist.php';
    echo "<hr />";
    require_once 'user/backgroundsearch.php';
}

require_once 'fw/footer.php';
?>