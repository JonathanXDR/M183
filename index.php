<?php
require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if (!isset($_COOKIE['username'])) {
    $logger->log('WARN', 'Unauthorized access attempt to index.php');
    header("Location: login.php");
    exit();
}

$logger->log('INFO', 'User accessed the index page.', ['username' => $_COOKIE['username']]);

require_once 'fw/header.php';
?>
<h2>Welcome,
    <?= htmlspecialchars($_COOKIE['username'], ENT_QUOTES, 'UTF-8'); ?>!
</h2>

<?php
if (isset($_COOKIE['userid'])) {
    require_once 'user/tasklist.php';
    echo "<hr />";
    require_once 'user/backgroundsearch.php';
}

require_once 'fw/footer.php';
?>