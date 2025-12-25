<?php
require_once 'db_connect.php';

session_start(); // Start the session
// Force login to see bookings
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT r.*, cs.make AS brand, cs.model, cs.transmission_type, cs.fuel_type, c.car_id
    , o.city AS pickup_location, r.reservation_status
    FROM reservations r
    JOIN cars c ON r.car_id = c.car_id
    JOIN car_specs cs ON c.spec_id = cs.spec_id
    JOIN offices o ON r.pickup_office_id = o.office_id
    WHERE r.customer_id = ?
    ORDER BY r.reservation_id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - vectoRental</title>
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
            <a href="my-reservations.php" class="active">My Bookings</a>
            <a href="contact-us.php">Contact</a>
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

    <section class="bookings-section">
        <div class="container">
            <div class="section-header">
                <h1>My Bookings</h1>
                <p>View and manage your car rental reservations</p>
                <?php if(isset($_GET['msg']) && $_GET['msg']=='success'): ?>
                    <p style="color: green; font-weight: bold;">Booking Successful!</p>
                <?php endif; ?>
            </div>

            <div class="bookings-grid">
                <?php if(count($bookings) > 0): ?>
                    <?php foreach($bookings as $booking): ?>
                    <div class="booking-card">
                        <div class="booking-header">
                            <div class="booking-id">
                                <span>#R<?php echo $booking['reservation_id']; ?></span>
                            </div>
                            <span class="status-badge <?php echo strtolower($booking['reservation_status']); ?>">
                                <?php echo ucfirst($booking['reservation_status']); ?>
                            </span>
                        </div>
                        <div class="booking-car">
                            <div class="car-image">
                                <?php
                                // Assign images based on brand/model (same as reservation.php)
                                $img_map = [
                                    'Porsche 911' => 'WhatsApp Image 2025-12-24 at 3.26.53 PM.jpeg',
                                    'BMW 5 Series' => 'WhatsApp Image 2025-12-24 at 3.40.16 PM.jpeg',
                                    'Chevrolet Optra' => 'WhatsApp Image 2025-12-22 at 7.00.07 PM.jpeg',
                                    'Kia Cerato' => 'WhatsApp Image 2025-12-22 at 7.39.30 PM.jpeg',
                                    'Fiat Tipo' => 'WhatsApp Image 2025-12-22 at 6.57.55 PM.jpeg',
                                    'Kia Sportage' => 'WhatsApp Image 2025-12-24 at 3.50.19 PM.jpeg',
                                    'Jeep Compass' => 'WhatsApp Image 2025-12-22 at 6.53.08 PM.jpeg',
                                    'Audi A4' => 'WhatsApp Image 2025-12-22 at 7.39.30 PM.jpeg',
                                    'BMW X5' => 'WhatsApp Image 2025-12-24 at 3.40.16 PM.jpeg',
                                    'Toyota Hilux' => 'WhatsApp Image 2025-12-22 at 7.00.07 PM.jpeg',
                                    'Isuzu D-Max' => 'WhatsApp Image 2025-12-22 at 6.53.08 PM.jpeg',
                                ];
                                $car_key = $booking['brand'] . ' ' . $booking['model'];
                                $img_file = isset($img_map[$car_key]) ? $img_map[$car_key] : 'https://via.placeholder.com/400x250';
                                ?>
                                <img src="<?php echo $img_file; ?>" alt="<?php echo htmlspecialchars($car_key); ?>">
                            </div>
                            <div class="car-details">
                                <h3><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></h3>
                                <div class="car-specs">
                                    <span><?php echo htmlspecialchars($booking['transmission_type']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="booking-details">
                            <div class="detail">
                                <span class="label">Pickup:</span>
                                <span class="value"><?php echo htmlspecialchars($booking['pickup_location']); ?></span>
                            </div>
                            <div class="detail">
                                <span class="label">Date:</span>
                                <span class="value"><?php echo $booking['pickup_date']; ?></span>
                            </div>
                        </div>
                        <div class="booking-footer">
                            <?php if ($booking['reservation_status'] === 'Active' || $booking['reservation_status'] === 'Confirmed'): ?>
                                <form action="cancel_reservation.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="reservation_id" value="<?php echo $booking['reservation_id']; ?>">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                                </form>
                            <?php endif; ?>
                            <div class="price">
                                <span class="amount"><?php echo $booking['total_price']; ?> <small>EGP</small></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>No Bookings Yet</h3>
                        <a href="reservation.php" class="btn btn-primary">Book a Car</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <script src="script.js"></script>
</body>
</html>