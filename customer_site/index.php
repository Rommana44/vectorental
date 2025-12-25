<?php
// Hidden redirect: if URL ends with /dashboerd or /index.php/dashboerd, redirect to dashboard login
$uri = $_SERVER['REQUEST_URI'];
if (preg_match('#/customer_site(/index\.php)?/dashboerd/?$#', $uri)) {
    header('Location: /employee_dashboerd/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vectoRental - Premium Car Rentals</title>
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
            <a href="index.php" class="active">Home</a>
            <a href="reservation.php">Reservations</a>
            <a href="my-reservations.php">My Bookings</a>
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

    <section class="hero">
        <div class="hero-content">
            <h1>Premium Car Rentals Made Simple</h1>
            <p>Experience luxury and comfort with our wide selection of premium vehicles.</p>
            <a href="reservation.php" class="cta-button">Book Now</a>
        </div>
    </section>

    <section class="cars">
        <div class="section-title">
            <h2>Our Fleet</h2>
            <p>Choose from our premium selection</p>
        </div>

        <div class="car-grid">
            <div class="car-card">
                <div class="car-image" style="width:100%;height:250px;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    <img src="WhatsApp%20Image%202025-12-24%20at%203.50.19%20PM.jpeg" alt="Porsche 911" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                </div>
                <div class="car-details">
                    <h3>Porsche 911</h3>
                    <div class="car-price">
                        <div class="price">$299<small>/day</small></div>
                        <a href="reservation.php" class="btn">Rent Now</a>
                    </div>
                </div>
            </div>
            <div class="car-card">
                <div class="car-image" style="width:100%;height:250px;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    <img src="WhatsApp%20Image%202025-12-24%20at%203.40.16%20PM.jpeg" alt="BMW 5 Series" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                </div>
                <div class="car-details">
                    <h3>BMW 5 Series</h3>
                    <div class="car-price">
                        <div class="price">$199<small>/day</small></div>
                        <a href="reservation.php" class="btn">Rent Now</a>
                    </div>
                </div>
            </div>
            <div class="car-card">
                <div class="car-image" style="width:100%;height:250px;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    <img src="WhatsApp%20Image%202025-12-24%20at%203.26.53%20PM.jpeg" alt="Aston Martin" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                </div>
                <div class="car-details">
                        <h3>Aston Martin</h3>
                    <div class="car-price">
                        <div class="price">$179<small>/day</small></div>
                        <a href="reservation.php" class="btn">Rent Now</a>
                    </div>
                </div>
            </div>
            <div class="car-card">
                <div class="car-image" style="width:100%;height:250px;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    <img src="WhatsApp%20Image%202025-12-22%20at%207.39.30%20PM.jpeg" alt="Mercedes-Benz E200" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                </div>
                <div class="car-details">
                    <h3>Mercedes-Benz E200</h3>
                    <div class="car-price">
                        <div class="price">$249<small>/day</small></div>
                        <a href="reservation.php" class="btn">Rent Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us box will be in the footer -->
    <footer class="footer">
        <div class="footer-content" style="display:flex;flex-wrap:wrap;gap:40px;justify-content:center;align-items:flex-start;">
            <div class="footer-section" style="flex:1 1 220px;min-width:200px;max-width:300px;">
                <h3 style="color:#fff;">Quick Links</h3>
                <ul style="list-style:none;padding:0;">
                    <li><a href="index.php" style="color:#fff;text-decoration:none;">Home</a></li>
                    <li><a href="reservation.php" style="color:#fff;text-decoration:none;">Reservations</a></li>
                    <li><a href="my-reservations.php" style="color:#fff;text-decoration:none;">My Bookings</a></li>
                    <li><a href="contact-us.php" style="color:#fff;text-decoration:none;">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-section" style="flex:2 1 320px;min-width:220px;max-width:500px;background:#f9fafb;padding:1.5rem 1.5rem 1rem 1.5rem;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                <h3 style="margin-bottom:0.7rem;">About Us</h3>
                <p style="font-size:1.05rem;color:#444;line-height:1.7;">
                    vectoRental is dedicated to providing a premium car rental experience for every customer. With a diverse fleet of luxury and economy vehicles, we make it easy to find the perfect car for your journey.<br>
                    Our mission is to deliver comfort, safety, and convenience—whether you’re traveling for business or leisure. Enjoy transparent pricing, flexible reservations, and top-notch customer support every step of the way.
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 vectoRental. All rights reserved.</p>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>