<?php

    // Check if the user is logged in
    if (!isset($_COOKIE['userid'])) {
        header("Location: /");
        exit();
    }

    require_once 'fw/header.php';
?>

FORM f. CREATE

<?php
    require_once 'fw/footer.php';
?>