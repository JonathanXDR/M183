<?php

require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

$logger->log('INFO', 'User logged out.', ['username' => $_COOKIE['username'] ?? 'Unknown']);

unset($_COOKIE['username']);
setcookie('username', '', -1, '/');
unset($_COOKIE['userid']);
setcookie('userid', '', -1, '/');

header("Location: /");
exit();
?>