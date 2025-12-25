<?php
/**
 * Admin Dashboard
 * Displays key metrics for admins.
 */

include '../../auth_guard.php';
require_once '../../db_connect.php';

// Check if admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../employee/employee-dashboard.php");
    exit();
}

if (!isset($pdo)) {
    $totalEmployees = 'PDO not set';
    $totalCars = 'PDO not set';
    $totalReservations = 'PDO not set';
    $totalRevenue = 'PDO not set';
} else {
    try {
        // Total Employees
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM employees");
        $totalEmployees = $stmt->fetch()['total'];

        // Total Cars
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM cars");
        $totalCars = $stmt->fetch()['total'];

        // Total Reservations
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM reservations");
        $totalReservations = $stmt->fetch()['total'];

        // Total Revenue (sum of completed reservations)
        $stmt = $pdo->query("
            SELECT SUM(cc.base_price_per_day * DATEDIFF(r.return_date, r.pickup_date)) as revenue
            FROM reservations r
            JOIN cars car ON r.car_id = car.car_id
            JOIN car_specs cs ON car.spec_id = cs.spec_id
            JOIN car_categories cc ON cs.category_id = cc.category_id
            WHERE r.reservation_status = 'Completed'
        ");
        $totalRevenue = $stmt->fetch()['revenue'] ?? 0;
    } catch (PDOException $e) {
        error_log("Dashboard query failed: " . $e->getMessage());
        $totalEmployees = $totalCars = $totalReservations = $totalRevenue = 'Error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Vectorental</title>
<link rel="stylesheet" href="../css/admin-dashboard.css">
</head>
<body>

<div class="dashboard">

  <aside class="sidebar">
    <h2 class="logo">Vectorental</h2>
    <nav>
      <a href="admin-dashboard.php" class="active">Dashboard</a>
      <a href="admin-employees.php">Employees</a>
      <a href="admin-offices.php">Offices</a>
      <a href="admin-reports.php">Reports</a>
    </nav>
  </aside>

  <main class="main-content">

    <header class="topbar">
      <span>Admin Dashboard</span>
      <a href="/vectorental/logout.php" class="logout">Logout</a>
    </header>

    <section class="cards">

      <div class="card">
        <h3>Total Employees</h3>
        <p><?php echo htmlspecialchars($totalEmployees); ?></p>
      </div>

      <div class="card">
        <h3>Total Cars</h3>
        <p><?php echo htmlspecialchars($totalCars); ?></p>
      </div>

      <div class="card">
        <h3>Total Reservations</h3>
        <p><?php echo htmlspecialchars($totalReservations); ?></p>
      </div>

      <div class="card">
        <h3>Total Revenue</h3>
        <p>$<?php echo htmlspecialchars(is_numeric($totalRevenue) ? number_format($totalRevenue, 2) : $totalRevenue); ?></p>
      </div>

    </section>

  </main>
</div>

</body>
</html>