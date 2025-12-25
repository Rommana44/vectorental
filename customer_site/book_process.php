<?php
session_start();
require 'db_connect.php';

// --- SECURITY CHECK ---
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=login_required");
    exit();
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
        $car_id = $_POST['car_id'];
        $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : $_SESSION['user_id'];
        $pickup_office_id = $_POST['pickup_office_id'];
        $return_office_id = $_POST['return_office_id'];
        $pickup_date = $_POST['pickup_date'];
        $return_date = $_POST['return_date'];
        $payment_method = $_POST['payment_method'];

        // Get price from car
        $stmt = $pdo->prepare("SELECT cc.base_price_per_day FROM cars c JOIN car_specs cs ON c.spec_id = cs.spec_id JOIN car_categories cc ON cs.category_id = cc.category_id WHERE c.car_id = ?");
        $stmt->execute([$car_id]);
        $car = $stmt->fetch();
        if ($car) {
            // Calculate total price (days difference)
            $days = (strtotime($return_date) - strtotime($pickup_date)) / (60*60*24);
            $days = max(1, $days);
            $total = $car['base_price_per_day'] * $days;

            // Validate customer_id exists in customers table
            $cust_stmt = $pdo->prepare("SELECT customer_id FROM customers WHERE customer_id = ?");
            $cust_stmt->execute([$customer_id]);
            $cust = $cust_stmt->fetch();
            if (!$cust) {
                throw new Exception('Invalid customer ID');
            }

            // Validate office IDs
            $office_stmt = $pdo->prepare("SELECT office_id FROM offices WHERE office_id = ?");
            $office_stmt->execute([$pickup_office_id]);
            $pickup_office = $office_stmt->fetch();
            $office_stmt->execute([$return_office_id]);
            $return_office = $office_stmt->fetch();
            if (!$pickup_office || !$return_office) {
                throw new Exception('Invalid office ID');
            }

            // Insert reservation
            $sql = "INSERT INTO reservations (customer_id, car_id, pickup_office_id, return_office_id, booking_date, pickup_date, return_date, total_price, reservation_status) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, 'Confirmed')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$customer_id, $car_id, $pickup_office_id, $return_office_id, $pickup_date, $return_date, $total]);
            $reservation_id = $pdo->lastInsertId();

            // Insert payment record
            $pay_stmt = $pdo->prepare("INSERT INTO payments (reservation_id, payment_date, amount, payment_method) VALUES (?, CURDATE(), ?, ?)");
            $pay_stmt->execute([$reservation_id, $total, $payment_method]);

            header("Location: my-reservations.php?msg=success");
            exit();
        } else {
            throw new Exception('Car not found');
        }
    }
    // If something goes wrong, go back to cars
    header("Location: reservation.php?msg=error");
    exit();
} catch (Exception $e) {
    echo '<div style="max-width:600px;margin:40px auto;padding:2rem;background:#fff3f3;border:1px solid #ffcccc;color:#b30000;font-weight:600;border-radius:8px;">Booking Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    echo '<a href="reservation.php" style="display:block;text-align:center;margin-top:2rem;">Back to Reservations</a>';
}
?>