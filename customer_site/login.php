
<?php
session_start();
require_once 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && isset($user['password_hash']) && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['customer_id'];
        $_SESSION['customer_id'] = $user['customer_id'];
        $_SESSION['user_name'] = $user['first_name'];
        // Redirect to Profile page after successful login
        header("Location: profile.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - vectoRental</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <a href="index.php" class="logo">
            <img src="Gemini_Generated_Image_983j9g983j9g983j.png" alt="Gemini Logo" class="logo-img">
            <span>vectoRental</span>
        </a>
        <!-- session_start moved to top -->
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="reservation.php">Reservations</a>
            <a href="my-reservations.php">My Bookings</a>
            <a href="contact-us.php">Contact</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <span style="color:#333;font-weight:600;margin-right:10px;">Welcome, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User'; ?></span>
                <a href="profile.php">Profile</a>
                <a href="edit-profile.php">Edit Profile</a>
                <a href="logout.php" class="btn btn-signup">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-login active">Login</a>
                <a href="signup.php" class="btn btn-signup">Sign Up</a>
            <?php endif; ?>
        </nav>
        <div class="hamburger">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </header>

    <main class="auth-container">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p>Login to access your account</p>
            
            <?php if($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <?php if(isset($_GET['msg']) && $_GET['msg']=='login_required'): ?>
                <p style="color: orange; font-weight: bold;">Please login to book a car.</p>
            <?php endif; ?>
        </div>

        <form class="auth-form" method="POST" action="login.php">
            <div class="form-group">
                <label for="login-email">Email Address</label>
                <input type="email" name="email" id="login-email" class="form-control" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="login-password">Password</label>
                <div class="password-input">
                    <input type="password" name="password" id="login-password" class="form-control" placeholder="Enter your password" required>
                </div>
            </div>

            <button type="submit" class="btn-auth">Login to Your Account</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="signup.php">Create an account</a>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>