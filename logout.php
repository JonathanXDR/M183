<?php
session_start();
require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if (isset($_SESSION['username'])) {
    $logger->log('INFO', 'User logged out.', ['username' => $_SESSION['username']]);
    $_SESSION = array();
    session_destroy();
}

header("Location: login.php");
exit();
?>