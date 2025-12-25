<?php
/**
 * Admin Reports
 * Handles report generation using stored procedures.
 */

include '../../auth_guard.php';
require_once '../../db_connect.php';

// Check if admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../employee/employee-dashboard.php");
    exit();
}

// Initialize report data
$reportData = null;
$message = '';
$currentTab = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['report_type'] ?? '';

    try {
        if ($reportType === 'period') {
            $startDate = $_POST['start_date'] ?? '';
            $endDate = $_POST['end_date'] ?? '';
            if ($startDate && $endDate) {
                $stmt = $pdo->prepare("CALL Report_ReservationsByPeriod(?, ?)");
                $stmt->execute([$startDate, $endDate]);
                $reportData = $stmt->fetchAll();
                $currentTab = 'reservations-period';
            }
        } elseif ($reportType === 'office_revenue') {
            $stmt = $pdo->query("CALL Report_OfficeRevenue()");
            $reportData = $stmt->fetchAll();
            $currentTab = 'office-revenue';
        } elseif ($reportType === 'status_summary') {
            $stmt = $pdo->query("CALL Report_StatusSummary()");
            $reportData = $stmt->fetchAll();
            $currentTab = 'reservation-status';
        } elseif ($reportType === 'daily_payments') {
            $startDate = $_POST['start_date'] ?? '';
            $endDate = $_POST['end_date'] ?? '';
            if ($startDate && $endDate) {
                $stmt = $pdo->prepare("CALL Report_DailyPayments(?, ?)");
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Reports | Vectorental</title>
<link rel="stylesheet" href="../css/admin-dashboard.css">
<link rel="stylesheet" href="../css/admin-reports.css">
</head>
<body>
<div class="dashboard">
  <aside class="sidebar">
    <h2 class="logo">Vectorental</h2>
    <nav>
      <a href="admin-dashboard.php">Dashboard</a>
      <a href="admin-employees.php">Employees</a>
      <a href="admin-offices.php">Offices</a>
      <a href="admin-reports.php" class="active">Reports</a>
    </nav>
  </aside>

  <main class="main-content">
    <header class="topbar">
      <span>Admin Reports</span>
      <a href="../../logout.php" class="logout">Logout</a>
    </header>

    <section class="content">
      <h2>Admin Reports</h2>

      <?php if (!empty($message)): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>

      <div class="tabs">
        <button class="tab-btn <?php echo $currentTab === 'reservations-period' ? 'active' : ''; ?>" data-target="reservations-period">Reservations by Period</button>
        <button class="tab-btn <?php echo $currentTab === 'office-revenue' ? 'active' : ''; ?>" data-target="office-revenue">Office Revenue</button>
        <button class="tab-btn <?php echo $currentTab === 'reservation-status' ? 'active' : ''; ?>" data-target="reservation-status">Reservation Status Summary</button>
        <button class="tab-btn <?php echo $currentTab === 'daily-payments' ? 'active' : ''; ?>" data-target="daily-payments">Daily Payments</button>
      </div>

      <!-- Reports Sections -->
      <div id="reservations-period" class="tab-content <?php echo $currentTab === 'reservations-period' ? 'active' : ''; ?>">
        <form class="report-form" method="post">
          <input type="hidden" name="report_type" value="period">
          <label>From:</label><input type="date" name="start_date" value="<?php echo htmlspecialchars($_POST['start_date'] ?? ''); ?>" required>
          <label>To:</label><input type="date" name="end_date" value="<?php echo htmlspecialchars($_POST['end_date'] ?? ''); ?>" required>
          <button type="submit">Generate</button>
        </form>
        <div class="report-result">
          <?php if ($reportData && $currentTab === 'reservations-period'): ?>
          <table>
            <thead>
              <tr>
                <th>Reservation ID</th>
                <th>Customer</th>
                <th>Car</th>
                <th>Pickup Date</th>
                <th>Return Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reportData as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['reservation_id'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['customer'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['car'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['pickup_date'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['return_date'] ?? ''); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php elseif ($currentTab === 'reservations-period'): ?>
          <p>No records found for the selected period.</p>
          <?php endif; ?>
        </div>
      </div>

      <div id="office-revenue" class="tab-content <?php echo $currentTab === 'office-revenue' ? 'active' : ''; ?>">
        <form class="report-form" method="post">
          <input type="hidden" name="report_type" value="office_revenue">
          <button type="submit">Generate</button>
        </form>
        <div class="report-result">
          <?php if ($reportData && $currentTab === 'office-revenue'): ?>
          <table>
            <thead>
              <tr>
                <th>Office</th>
                <th>Revenue</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reportData as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['office'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['revenue'] ?? ''); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php elseif ($currentTab === 'office-revenue'): ?>
          <p>No records found.</p>
          <?php endif; ?>
        </div>
      </div>

      <div id="reservation-status" class="tab-content <?php echo $currentTab === 'reservation-status' ? 'active' : ''; ?>">
        <form class="report-form" method="post">
          <input type="hidden" name="report_type" value="status_summary">
          <button type="submit">Generate</button>
        </form>
        <div class="report-result">
          <?php if ($reportData && $currentTab === 'reservation-status'): ?>
          <ul>
            <?php foreach ($reportData as $row): ?>
            <li><?php echo htmlspecialchars($row['status'] ?? ''); ?>: <?php echo htmlspecialchars($row['count'] ?? ''); ?></li>
            <?php endforeach; ?>
          </ul>
          <?php elseif ($currentTab === 'reservation-status'): ?>
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
            <thead>
              <tr>
                <th>Date</th>
                <th>Payments</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reportData as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['date'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['payments'] ?? ''); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php elseif ($currentTab === 'daily-payments'): ?>
          <p>No records found for the selected period.</p>
          <?php endif; ?>
        </div>
      </div>

    </section>
  </main>
</div>

<script>
// Tab switching logic
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