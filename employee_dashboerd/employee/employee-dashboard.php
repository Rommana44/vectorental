<?php
/**
 * Employee Dashboard
 * Displays dashboard with dynamic stats from database.
 */

include '../../auth_guard.php';
require_once '../../db_connect.php';

try {
    // Fetch total cars
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM cars");
    $totalCars = $stmt->fetch()['total'];

    // Fetch active rentals (assuming status 'Rented')
    $stmt = $pdo->query("SELECT COUNT(*) as active FROM cars WHERE status = 'Rented'");
    $activeRentals = $stmt->fetch()['active'];

    // Fetch available cars (assuming status 'Active')
    $stmt = $pdo->query("SELECT COUNT(*) as available FROM cars WHERE status = 'Active'");
    $availableCars = $stmt->fetch()['available'];
} catch (PDOException $e) {
    // Handle errors gracefully
    error_log("Dashboard query failed: " . $e->getMessage());
    $totalCars = $activeRentals = $availableCars = 'Error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Vectorental | Employee Dashboard</title>
<link rel="stylesheet" href="../css/employee-dashboard.css">
</head>
<body>
<div class="dashboard">

  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="logo">Vectorental</h2>
    <nav>
      <a href="employee-dashboard.php" class="active">Dashboard</a>
      <a href="employee-cars.php">Cars</a>
      <a href="employee-reservations.php">Reservations</a>
      <a href="employee-add-car.php">Add Car</a>
      <a href="employee-car-status.php">Car Status</a>
      <a href="employee-reports.php">Reports</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Top Bar -->
    <header class="topbar">
      <span>Employee Dashboard</span>
      <a href="../../logout.php" class="logout">Logout</a>
    </header>

    <!-- Cards Section -->
    <section class="cards">
      <div class="card">
        <h3>Total Cars</h3>
        <p><?php echo htmlspecialchars($totalCars); ?></p>
      </div>

      <div class="card">
        <h3>Active Rentals</h3>
        <p><?php echo htmlspecialchars($activeRentals); ?></p>
      </div>

      <div class="card">
        <h3>Available Cars</h3>
        <p><?php echo htmlspecialchars($availableCars); ?></p>
      </div>
    </section>
  </main>
</div>
</body>
</html>