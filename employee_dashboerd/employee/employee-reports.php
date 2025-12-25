<?php
/**
 * Employee Reports
 * Displays various reports for employees.
 */

include '../../auth_guard.php';
require_once '../../db_connect.php';

// Initialize
$reportData = null;
$message = '';
$currentTab = '';

// Handle submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['report_type'] ?? '';

    try {
        if ($reportType === 'reservations_period') {
            $startDate = $_POST['start_date'] ?? '';
            $endDate = $_POST['end_date'] ?? '';
            if ($startDate && $endDate) {
                $stmt = $pdo->prepare("
                    SELECT r.reservation_id, CONCAT(cust.first_name, ' ', cust.last_name) as name, r.pickup_date, r.return_date, r.reservation_status
                    FROM reservations r
                    JOIN customers cust ON r.customer_id = cust.customer_id
                    WHERE DATE(r.pickup_date) BETWEEN ? AND ?
                    ORDER BY r.pickup_date
                ");
                $stmt->execute([$startDate, $endDate]);
                $reportData = $stmt->fetchAll();
                $currentTab = 'reservations-period';
            }
        } elseif ($reportType === 'car_status_day') {
            $date = $_POST['date'] ?? '';
            if ($date) {
                $stmt = $pdo->prepare("
                    SELECT c.plate_id, cs.make, cs.model, c.status
                    FROM cars c
                    JOIN car_specs cs ON c.spec_id = cs.spec_id
                    ORDER BY c.plate_id
                ");
                $stmt->execute();
                $reportData = $stmt->fetchAll();
                $currentTab = 'car-status-day';
            }
        } elseif ($reportType === 'customer_reservations') {
            $customerId = $_POST['customer_id'] ?? '';
            if ($customerId) {
                $stmt = $pdo->prepare("
                    SELECT r.reservation_id, r.pickup_date, r.return_date, r.reservation_status
                    FROM reservations r
                    WHERE r.customer_id = ?
                    ORDER BY r.pickup_date DESC
                ");
                $stmt->execute([$customerId]);
                $reportData = $stmt->fetchAll();
                $currentTab = 'customer-reservations';
            }
        } elseif ($reportType === 'daily_payments') {
            $startDate = $_POST['start_date'] ?? '';
            $endDate = $_POST['end_date'] ?? '';
            if ($startDate && $endDate) {
                // Assuming payments table or calculate from reservations
                $stmt = $pdo->prepare("
                    SELECT DATE(r.pickup_date) as date, SUM(cc.base_price_per_day * DATEDIFF(r.return_date, r.pickup_date)) as payments
                    FROM reservations r
                    JOIN cars car ON r.car_id = car.car_id
                    JOIN car_specs cs ON car.spec_id = cs.spec_id
                    JOIN car_categories cc ON cs.category_id = cc.category_id
                    WHERE r.reservation_status = 'Completed' AND DATE(r.pickup_date) BETWEEN ? AND ?
                    GROUP BY DATE(r.pickup_date)
                    ORDER BY date
                ");
                $stmt->execute([$startDate, $endDate]);
                $reportData = $stmt->fetchAll();
                $currentTab = 'daily-payments';
            }
        }
    } catch (PDOException $e) {
        error_log("Report query failed: " . $e->getMessage());
        $message = 'Error generating report.';
    }
}

// Fetch customers for dropdown
$customers = [];
try {
    $stmt = $pdo->query("SELECT customer_id, CONCAT(first_name, ' ', last_name) as name FROM customers ORDER BY name");
    $customers = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Customers query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reports | Vectorental</title>
<link rel="stylesheet" href="../css/employee-dashboard.css">
<link rel="stylesheet" href="../css/employee-reports.css">
</head>
<body>
<div class="dashboard">
  <aside class="sidebar">
    <h2 class="logo">Vectorental</h2>
    <nav>
      <a href="employee-dashboard.php">Dashboard</a>
      <a href="employee-cars.php">Cars</a>
      <a href="employee-reservations.php">Reservations</a>
      <a href="employee-add-car.php">Add Car</a>
      <a href="employee-car-status.php">Car Status</a>
      <a href="employee-reports.php" class="active">Reports</a>
    </nav>
  </aside>

  <main class="main-content">
    <header class="topbar">
      <span>Reports</span>
      <a href="../../logout.php" class="logout">Logout</a>
    </header>

    <section class="content">
      <h2>Employee Reports</h2>

      <?php if (!empty($message)): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>

      <div class="tabs">
        <button class="tab-btn <?php echo $currentTab === 'reservations-period' ? 'active' : ''; ?>" data-target="reservations-period">Reservations by Period</button>
        <button class="tab-btn <?php echo $currentTab === 'car-status-day' ? 'active' : ''; ?>" data-target="car-status-day">Car Status on Day</button>
        <button class="tab-btn <?php echo $currentTab === 'customer-reservations' ? 'active' : ''; ?>" data-target="customer-reservations">Customer Reservations</button>
        <button class="tab-btn <?php echo $currentTab === 'daily-payments' ? 'active' : ''; ?>" data-target="daily-payments">Daily Payments</button>
      </div>

      <div id="reservations-period" class="tab-content <?php echo $currentTab === 'reservations-period' ? 'active' : ''; ?>">
        <form class="report-form" method="post">
          <input type="hidden" name="report_type" value="reservations_period">
          <label>From:</label><input type="date" name="start_date" value="<?php echo htmlspecialchars($_POST['start_date'] ?? ''); ?>" required>
          <label>To:</label><input type="date" name="end_date" value="<?php echo htmlspecialchars($_POST['end_date'] ?? ''); ?>" required>
          <button type="submit">Generate</button>
        </form>
        <div class="report-result">
          <?php if ($reportData && $currentTab === 'reservations-period'): ?>
          <table>
            <thead><tr><th>Reservation ID</th><th>Customer</th><th>Pickup Date</th><th>Return Date</th><th>Status</th></tr></thead>
            <tbody>
              <?php foreach ($reportData as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['reservation_id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['pickup_date']); ?></td>
                <td><?php echo htmlspecialchars($row['return_date']); ?></td>
                <td><?php echo htmlspecialchars($row['reservation_status']); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php elseif ($currentTab === 'reservations-period'): ?>
          <p>No records found.</p>
          <?php endif; ?>
        </div>
      </div>

      <div id="car-status-day" class="tab-content <?php echo $currentTab === 'car-status-day' ? 'active' : ''; ?>">
        <form class="report-form" method="post">
          <input type="hidden" name="report_type" value="car_status_day">
          <label>Date:</label><input type="date" name="date" value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>" required>
          <button type="submit">Generate</button>
        </form>
        <div class="report-result">
          <?php if ($reportData && $currentTab === 'car-status-day'): ?>
          <table>
            <thead><tr><th>Plate ID</th><th>Model</th><th>Status</th></tr></thead>
            <tbody>
              <?php foreach ($reportData as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['plate_id']); ?></td>
                <td><?php echo htmlspecialchars($row['make'] . ' ' . $row['model']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php elseif ($currentTab === 'car-status-day'): ?>
          <p>No records found.</p>
          <?php endif; ?>
        </div>
      </div>

      <div id="customer-reservations" class="tab-content <?php echo $currentTab === 'customer-reservations' ? 'active' : ''; ?>">
        <form class="report-form" method="post">
          <input type="hidden" name="report_type" value="customer_reservations">
          <label>Customer:</label>
          <select name="customer_id" required>
            <option value="">Select Customer</option>
            <?php foreach ($customers as $cust): ?>
            <option value="<?php echo htmlspecialchars($cust['customer_id']); ?>" <?php echo ($_POST['customer_id'] ?? '') == $cust['customer_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cust['name']); ?></option>
            <?php endforeach; ?>
          </select>
          <button type="submit">Generate</button>
        </form>
        <div class="report-result">
          <?php if ($reportData && $currentTab === 'customer-reservations'): ?>
          <table>
            <thead><tr><th>Reservation ID</th><th>Pickup Date</th><th>Return Date</th><th>Status</th></tr></thead>
            <tbody>
              <?php foreach ($reportData as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['reservation_id']); ?></td>
                <td><?php echo htmlspecialchars($row['pickup_date']); ?></td>
                <td><?php echo htmlspecialchars($row['return_date']); ?></td>
                <td><?php echo htmlspecialchars($row['reservation_status']); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php elseif ($currentTab === 'customer-reservations'): ?>
          <p>No records found.</p>
          <?php endif; ?>
        </div>
      </div>

      <div id="daily-payments" class="tab-content <?php echo $currentTab === 'daily-payments' ? 'active' : ''; ?>">
        <form class="report-form" method="post">
          <input type="hidden" name="report_type" value="daily_payments">
          <label>From:</label><input type="date" name="start_date" value="<?php echo htmlspecialchars($_POST['start_date'] ?? ''); ?>" required>
          <label>To:</label><input type="date" name="end_date" value="<?php echo htmlspecialchars($_POST['end_date'] ?? ''); ?>" required>
          <button type="submit">Generate</button>
        </form>
        <div class="report-result">
          <?php if ($reportData && $currentTab === 'daily-payments'): ?>
          <table>
            <thead><tr><th>Date</th><th>Payments</th></tr></thead>
            <tbody>
              <?php foreach ($reportData as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo htmlspecialchars($row['payments']); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php elseif ($currentTab === 'daily-payments'): ?>
          <p>No records found.</p>
          <?php endif; ?>
        </div>
      </div>

    </section>
  </main>
</div>

<script>
// Tab switching
document.querySelectorAll(".tab-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("active"));
    document.querySelectorAll(".tab-content").forEach(c => c.classList.remove("active"));
    btn.classList.add("active");
    document.getElementById(btn.dataset.target).classList.add("active");
  });
});
</script>
</body>
</html>