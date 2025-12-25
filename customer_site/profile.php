<?php
require_once 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Always set customer_id in session for booking
$_SESSION['customer_id'] = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user) {
    echo '<div style="max-width:600px;margin:40px auto;padding:2rem;background:#fff3f3;border:1px solid #ffcccc;color:#b30000;font-weight:600;border-radius:8px;">Profile Error: User not found.</div>';
    echo '<a href="logout.php" style="display:block;text-align:center;margin-top:2rem;">Logout</a>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-container { max-width: 600px; margin: 50px auto; padding: 2rem; background: #fff; box-shadow: 0 4px 24px rgba(0,0,0,0.08); border-radius: 12px; }
        .profile-container h2 { color: var(--primary-color); text-align: center; margin-bottom: 1.5rem; }
        .profile-item { margin-bottom: 1.2rem; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .profile-item label { font-weight: 600; color: var(--text-dark); display: block; margin-bottom: 0.3rem; }
        .profile-item p { color: var(--text-light); font-size: 1.05rem; margin-bottom: 0.2rem; }
        .btn-primary { background: var(--primary-color); color: #222; border: none; padding: 0.8rem 2rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: var(--transition); display: block; margin: 2rem auto 0; }
        .btn-primary:hover { background: var(--accent-color); }
    </style>
</head>
<body>
<header class="header">
    <a href="index.php" class="logo"><span>vectoRental</span></a>
    <nav class="nav-links">
        <a href="index.php">Home</a>
        <a href="reservation.php">Reservations</a>
        <a href="my-reservations.php">My Bookings</a>
        <a href="contact-us.php">Contact</a>
        <a href="profile.php" class="active">Profile</a>
        <a href="edit-profile.php">Edit Profile</a>
        <a href="logout.php" class="btn btn-signup">Logout</a>
    </nav>
</header>

<div class="profile-container">
    <h2>My Profile</h2>
    <div class="profile-item">
        <label>Name:</label>
        <p><?php echo isset($user['first_name']) ? htmlspecialchars($user['first_name'] . " " . $user['last_name']) : 'N/A'; ?></p>
    </div>
    <div class="profile-item">
        <label>Email:</label>
        <p><?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'N/A'; ?></p>
    </div>
    <div class="profile-item">
        <label>Phone:</label>
        <p><?php echo isset($user['phone_number']) ? htmlspecialchars($user['phone_number']) : 'N/A'; ?></p>
    </div>
    <div class="profile-item">
        <label>Driver License:</label>
        <p><?php echo isset($user['driver_license_number']) ? htmlspecialchars($user['driver_license_number']) : 'N/A'; ?></p>
    </div>
    <div class="profile-item">
        <label>Address:</label>
        <p><?php echo isset($user['address']) ? htmlspecialchars($user['address']) : 'N/A'; ?></p>
    </div>
    <a href="edit-profile.php" class="btn btn-primary">Edit Profile</a>
</div>
</body>
</html>