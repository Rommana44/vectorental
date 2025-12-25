<?php
/**
 * Authentication Guard
 * Checks if user is logged in, redirects to login if not.
 */

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: employee dashboerd/login.php");
    exit();
}
?>