<?php
/**
 * Login Handler
 * Handles POST requests for employee authentication.
 * Uses PDO prepared statements and password_verify for security.
 */

session_start();
require_once '../db_connect.php'; // Include database connection
if (!isset($pdo) || !$pdo) {
    error_log('DB connection failed or $pdo not set. Tried path: ' . __DIR__ . '/../db_connect.php');
    die('Database connection failed. Please contact admin.');
}

// Initialize error message
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs (though PDO will handle SQL injection)
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate inputs
    if (empty($username) || empty($password)) {
        $error = 'Invalid credentials';
    } else {
        try {
            // Prepare query to fetch employee data
            $stmt = $pdo->prepare("SELECT employee_id, password_hash, role, office_id, status FROM Employees WHERE username = ?");
            $stmt->execute([$username]);
            $employee = $stmt->fetch();

            if ($employee && password_verify($password, $employee['password_hash'])) {
                if (strtolower($employee['status']) !== 'active') {
                    $error = 'Your account is inactive. Please contact admin.';
                } else {
                    // Successful login: Set session variables
                    $_SESSION['user_id'] = $employee['employee_id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $employee['role'];
                    $_SESSION['office_id'] = $employee['office_id'];
                    // Redirect based on role
                    if ($employee['role'] === 'admin') {
                        header("Location: admin/admin-dashboard.php");
                    } else {
                        header("Location: employee/employee-dashboard.php");
                    }
                    exit();
                }
            } else {
                $error = 'Invalid credentials';
            }
        } catch (PDOException $e) {
            // Log error and show generic message
            error_log("Login query failed: " . $e->getMessage());
            $error = 'Invalid credentials';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Vectorental | Employee Login</title>
<link rel="stylesheet" href="employee-login.css?v=2">
</head>
<body>
<div class="login-container">
  <h1>Vectorental</h1>
  <p class="subtitle">Employee Portal</p>
  <?php if (!empty($error)): ?>
  <p class="error"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>
  <form class="login-form" method="post" action="login.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
</div>
</body>
</html>
