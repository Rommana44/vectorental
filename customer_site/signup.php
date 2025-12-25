<?php
require_once 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $license = trim($_POST['driver_license']);
    $address = trim($_POST['address']);

    $stmt = $pdo->prepare("SELECT customer_id FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $error = "Email already registered!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO customers (email, password_hash, first_name, last_name, phone_number, driver_license_number, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$email, $hashed_password, $first_name, $last_name, $phone, $license, $address])) {
            $new_id = $pdo->lastInsertId();
            session_start();
            $_SESSION['user_id'] = $new_id;
            $_SESSION['customer_id'] = $new_id;
            $_SESSION['user_name'] = $first_name;
            header("Location: profile.php");
            exit();
        } else {
            $error = "Registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - vectoRental</title>
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
        <?php session_start(); ?>
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="reservation.php">Reservations</a>
            <a href="my-reservations.php">My Bookings</a>
            <a href="contact-us.php">Contact</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="profile.php">Profile</a>
                <a href="edit-profile.php">Edit Profile</a>
                <a href="logout.php" class="btn btn-signup">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-login">Login</a>
                <a href="signup.php" class="btn btn-signup active">Sign Up</a>
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
            <h1>Create Your Account</h1>
            <?php if($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>

        <form class="auth-form" method="POST" action="signup.php">
            <div class="form-row">
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" name="phone" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Create Password</label>
                <div class="password-input">
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label for="driver-license">Driver's License Number</label>
                <input type="text" name="driver_license" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" required>
            </div>

            <button type="submit" class="btn-auth">Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Sign in</a>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>