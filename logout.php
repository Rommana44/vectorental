<?php
/**
 * Logout Handler
 * Destroys session and redirects to login.
 */

session_start();
session_destroy();
header("Location: /vectorental/employee_dashboerd/login.php");
exit();
?>