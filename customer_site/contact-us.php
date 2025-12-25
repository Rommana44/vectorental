<?php
require_once 'db_connect.php';
session_start();
$msg_status = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Ensure contact_messages table exists in DB first
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $email, $subject, $message])) {
        $msg_status = "Message sent successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - vectoRental</title>
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
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="reservation.php">Reservations</a>
            <a href="my-reservations.php">My Bookings</a>
            <a href="contact-us.php" class="active">Contact</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="profile.php">Profile</a>
                <a href="edit-profile.php">Edit Profile</a>
                <a href="logout.php" class="btn btn-signup">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-login">Login</a>
                <a href="signup.php" class="btn btn-signup">Sign Up</a>
            <?php endif; ?>
        </nav>
        <div class="hamburger">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </header>

    <section class="contact-section">
        <div class="container">
            <div class="section-header">
                <h1>Get in Touch</h1>
                <?php if($msg_status): ?>
                    <p style="color: green;"><?php echo $msg_status; ?></p>
                <?php endif; ?>
            </div>
            <div class="contact-form">
                <form method="POST" action="contact-us.php">
                    <div class="form-group"><input type="text" name="name" class="form-control" placeholder="Name" required></div>
                    <div class="form-group"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                    <div class="form-group"><input type="text" name="subject" class="form-control" placeholder="Subject" required></div>
                    <div class="form-group"><textarea name="message" class="form-control" rows="5" placeholder="Message" required></textarea></div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </section>
    <script src="script.js"></script>
</body>
</html>