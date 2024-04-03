<?php

require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if (isset($_COOKIE['username'])) {
    $logger->log('INFO', 'User logged out.', ['username' => $_COOKIE['username']]);
}

setcookie('username', '', time() - 3600, '/');
setcookie('userID', '', time() - 3600, '/');

header("Location: /");
exit();
?>