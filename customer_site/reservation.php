<?php
require_once 'db_connect.php';
session_start();
// Require login to reserve a car
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=login_required");
    exit();
}
// Fetch filter data from database

// Get all cities from offices
$cities = $pdo->query("SELECT DISTINCT city FROM offices")->fetchAll(PDO::FETCH_COLUMN);
// Get categories from car_categories that have active cars
$categories = $pdo->query("SELECT DISTINCT cc.category_name FROM car_categories cc JOIN car_specs cs ON cc.category_id = cs.category_id JOIN cars c ON cs.spec_id = c.spec_id WHERE c.status = 'Active'")->fetchAll(PDO::FETCH_COLUMN);
// Get brands from car_specs that have active cars
$brands = $pdo->query("SELECT DISTINCT cs.make FROM car_specs cs JOIN cars c ON cs.spec_id = c.spec_id WHERE c.status = 'Active'")->fetchAll(PDO::FETCH_COLUMN);

// Build car query based on filters

// Build car query with correct joins
$where = [];
$params = [];
if (!empty($_GET['city'])) {
    $where[] = "o.city = ?";
    $params[] = $_GET['city'];
}
if (!empty($_GET['category'])) {
    $where[] = "cc.category_name = ?";
    $params[] = $_GET['category'];
}
if (!empty($_GET['brand'])) {
    $where[] = "cs.make = ?";
    $params[] = $_GET['brand'];
}
$sql = "SELECT c.*, cs.make AS brand, cs.model, cs.transmission_type, cs.fuel_type, cc.category_name AS category, cc.base_price_per_day, o.city AS location FROM cars c JOIN car_specs cs ON c.spec_id = cs.spec_id JOIN car_categories cc ON cs.category_id = cc.category_id JOIN offices o ON c.current_office_id = o.office_id";
if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$cars = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vectoRental - Reserve a Car</title>
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
            <a href="reservation.php" class="active">Reservations</a>
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
    <section class="hero" style="height: 300px; min-height: 300px;">
        <div class="hero-content">
            <h1>Reserve Your Dream Car</h1>
            <p>Choose from our premium selection of vehicles.</p>
        </div>
    </section>

    <section class="filters-section" style="padding: 3rem 5%; background-color: #f9fafb;">
        <div class="container">
            <form action="reservation.php" method="GET">
                <div class="filters-grid">
                    <div class="form-group">
                        <label for="city">City</label>
                        <select name="city" id="city" class="form-control">
                            <option value="">Select City</option>
                            <?php foreach($cities as $city): ?>
                                <option value="<?php echo htmlspecialchars($city); ?>" <?php if(isset($_GET['city']) && $_GET['city']==$city) { echo 'selected'; } ?>><?php echo htmlspecialchars($city); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" <?php if(isset($_GET['category']) && $_GET['category']==$cat) { echo 'selected'; } ?>><?php echo htmlspecialchars($cat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand</label>
                        <select name="brand" id="brand" class="form-control">
                            <option value="">Select Brand</option>
                            <?php foreach($brands as $brand): ?>
                                <option value="<?php echo htmlspecialchars($brand); ?>" <?php if(isset($_GET['brand']) && $_GET['brand']==$brand) { echo 'selected'; } ?>><?php echo htmlspecialchars($brand); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="align-self: flex-end; height: 42px;">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="cars" style="padding: 5rem 5%;">
        <div class="container">
            <div class="section-title">
                <h2>Available Cars</h2>
                <p>Choose the perfect vehicle for your journey</p>
            </div>
            
            <div class="car-grid">
                <?php if(count($cars) > 0): ?>
                    <?php foreach($cars as $car): ?>
                    <div class="car-card">
                        <div class="car-image">
                            <?php
                            // Assign images based on brand/model
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
                            $car_key = $car['brand'] . ' ' . $car['model'];
                            $img_file = isset($img_map[$car_key]) ? $img_map[$car_key] : 'https://via.placeholder.com/400x250';
                            ?>
                            <img src="<?php echo $img_file; ?>" alt="<?php echo htmlspecialchars($car_key); ?>">
                            <span class="car-badge"><?php echo htmlspecialchars($car['category']); ?></span>
                        </div>
                        <div class="car-details">
                            <h3><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h3>
                            <div class="car-specs">
                                <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($car['location']); ?></span>
                                <span><i class="fas fa-car"></i> <?php echo htmlspecialchars($car['transmission_type'] ?? 'N/A'); ?></span>
                                <span><i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($car['fuel_type']); ?></span>
                            </div>
                            <div class="car-price">
                                <div class="price">$
                                <?php 
                                    // Use base_price_per_day from car_categories
                                    echo htmlspecialchars($car['base_price_per_day'] ?? 'N/A'); 
                                ?> <span>/day</span></div>
                                <?php if (strtolower($car['status']) === 'active'): ?>
                                    <a href="book_form.php?car_id=<?php echo $car['car_id']; ?>" class="btn btn-primary">Reserve Now</a>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled style="background:#ccc;cursor:not-allowed;">Not Available</button>
                                <?php endif; ?>
                                                    <span class="car-status-badge" style="position:absolute;top:12px;left:12px;padding:6px 16px;border-radius:16px;font-weight:600;background:#eee;color:#333;z-index:2;">
                                                        <?php echo htmlspecialchars(ucfirst($car['status'])); ?>
                                                    </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No cars found matching your criteria.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-bottom">
            <p>&copy; 2025 vectoRental. All rights reserved.</p>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>