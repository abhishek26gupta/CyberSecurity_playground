<?php
session_start();
session_unset();
session_destroy();

// Clear the user_role cookie if it exists (for the A08 vulnerability)
if (isset($_COOKIE['user_role'])) {
    setcookie('user_role', '', time() - 3600, '/');
}

header("Location: index.php");
exit();
?>