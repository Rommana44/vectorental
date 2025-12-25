<?php
session_start();
require_once 'db_connect.php';
if (!isset($_SESSION['user_id']) || !isset($_GET['car_id'])) {
    header("Location: reservation.php");
    exit();
}
$car_id = $_GET['car_id'];
// Fetch car details
$stmt = $pdo->prepare("SELECT c.car_id, cs.make, cs.model, cc.base_price_per_day FROM cars c JOIN car_specs cs ON c.spec_id = cs.spec_id JOIN car_categories cc ON cs.category_id = cc.category_id WHERE c.car_id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch();
// Fetch offices
$offices = $pdo->query("SELECT office_id, city FROM offices")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Booking</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .booking-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2rem 2.5rem;
        }
        .booking-container h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.3rem;
            display: block;
        }
        select, input[type="date"] {
            width: 100%;
            padding: 0.6rem;
            border-radius: 6px;
            border: 1px solid #eee;
            font-size: 1rem;
            margin-top: 0.2rem;
        }
        .btn-primary {
            background: var(--primary-color);
            color: #222;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        .btn-primary:hover {
            background: var(--accent-color);
        }
        .receipt {
            background: #f9fafb;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            padding: 1.2rem 1.5rem;
            margin-top: 2rem;
        }
        .receipt-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.7rem;
        }
        .receipt-label {
            color: var(--text-light);
        }
        .receipt-value {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <h2>Confirm Your Booking</h2>
        <form id="bookingForm" action="book_process.php" method="POST" onsubmit="return showReceipt(event)">
            <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
            <div class="form-group">
                <label>Car:</label>
                <span><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></span>
            </div>
            <div class="form-group">
                <label>Pickup Office:</label>
                <select name="pickup_office_id" id="pickup_office_id" required>
                    <?php foreach($offices as $office): ?>
                        <option value="<?php echo $office['office_id']; ?>"><?php echo htmlspecialchars($office['city']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Return Office:</label>
                <select name="return_office_id" id="return_office_id" required>
                    <?php foreach($offices as $office): ?>
                        <option value="<?php echo $office['office_id']; ?>"><?php echo htmlspecialchars($office['city']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Pickup Date:</label>
                <input type="date" name="pickup_date" id="pickup_date" required>
            </div>
            <div class="form-group">
                <label>Return Date:</label>
                <input type="date" name="return_date" id="return_date" required>
            </div>
            <div class="form-group">
                <label>Payment Method:</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Complete Reservation</button>
        </form>
        <div id="receipt" class="receipt" style="display:none;"></div>
    </div>
    <script>
    function showReceipt(e) {
        e.preventDefault();
        // Get form values
        var car = '<?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?>';
        var pickupOffice = document.getElementById('pickup_office_id').selectedOptions[0].text;
        var returnOffice = document.getElementById('return_office_id').selectedOptions[0].text;
        var pickupDate = document.getElementById('pickup_date').value;
        var returnDate = document.getElementById('return_date').value;
        var paymentMethod = document.getElementById('payment_method').value;
        var pricePerDay = <?php echo (float)$car['base_price_per_day']; ?>;
        var days = (new Date(returnDate) - new Date(pickupDate)) / (1000*60*60*24);
        days = Math.max(1, days);
        var total = pricePerDay * days;
        var receipt = document.getElementById('receipt');
        receipt.innerHTML = `
            <div class='receipt-title'>Booking Receipt</div>
            <div class='receipt-row'><span class='receipt-label'>Car:</span><span class='receipt-value'>${car}</span></div>
            <div class='receipt-row'><span class='receipt-label'>Pickup Office:</span><span class='receipt-value'>${pickupOffice}</span></div>
            <div class='receipt-row'><span class='receipt-label'>Return Office:</span><span class='receipt-value'>${returnOffice}</span></div>
            <div class='receipt-row'><span class='receipt-label'>Pickup Date:</span><span class='receipt-value'>${pickupDate}</span></div>
            <div class='receipt-row'><span class='receipt-label'>Return Date:</span><span class='receipt-value'>${returnDate}</span></div>
            <div class='receipt-row'><span class='receipt-label'>Payment Method:</span><span class='receipt-value'>${paymentMethod}</span></div>
            <div class='receipt-row'><span class='receipt-label'>Total Price:</span><span class='receipt-value'>EGP ${total.toFixed(2)}</span></div>
            <button onclick='document.getElementById("bookingForm").submit()' class='btn btn-primary' style='margin-top:1rem;'>Confirm & Pay</button>
        `;
        receipt.style.display = 'block';
        return false;
    }
    </script>
</body>
</html>
