<?php
require_once 'db_connect.php';

// Check if logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $license = trim($_POST['driver_license']);
    $sql = "UPDATE customers SET first_name=?, last_name=?, phone_number=?, address=?, driver_license_number=? WHERE customer_id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$first_name, $last_name, $phone, $address, $license, $user_id])) {
        $message = "Profile updated successfully!";
        // Update session name if changed
        $_SESSION['user_name'] = $first_name;
    } else {
        $message = "Error updating profile.";
    }
}
// Fetch current data to pre-fill the form
$stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - vectoRental</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .edit-container { max-width: 500px; margin: 50px auto; padding: 2rem; background: #fff; box-shadow: 0 4px 24px rgba(0,0,0,0.08); border-radius: 12px; }
        .edit-container h2 { color: var(--primary-color); text-align: center; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: var(--text-dark); }
        .form-control { width: 100%; padding: 10px; border: 1px solid #eee; border-radius: 6px; font-size: 1rem; }
        .btn-primary { background: var(--primary-color); color: #222; border: none; padding: 0.8rem 2rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: var(--transition); }
        .btn-primary:hover { background: var(--accent-color); }
        .success-msg { color: green; font-weight: 600; text-align: center; margin-bottom: 1rem; }
        .error-msg { color: #b30000; font-weight: 600; text-align: center; margin-bottom: 1rem; }
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
            <a href="profile.php">Profile</a>
            <a href="edit-profile.php" class="active">Edit Profile</a>
            <a href="logout.php" class="btn btn-signup">Logout</a>
        </nav>
    </header>
    <div class="edit-container">
        <h2>Edit Profile</h2>
        <?php if($message): ?>
            <div class="success-msg"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="edit-profile.php">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
            </div>
            <div class="form-group">
                <label>Driver License</label>
                <input type="text" name="driver_license" class="form-control" value="<?php echo htmlspecialchars($user['driver_license_number']); ?>" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control" required><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Save Changes</button>
        </form>
    </div>
</body>
</html>