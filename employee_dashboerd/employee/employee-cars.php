<?php
/**
 * Employee Cars List
 * Displays a list of all cars with their details.
 */

include '../../auth_guard.php';
require_once '../../db_connect.php';

$cars = [];

// Fetch cars with specs and office
try {
    $stmt = $pdo->query("
        SELECT c.plate_id, cs.make, cs.model, cs.year, c.color, c.status, o.city
        FROM cars c
        JOIN car_specs cs ON c.spec_id = cs.spec_id
        JOIN offices o ON c.current_office_id = o.office_id
        ORDER BY c.plate_id
    ");
    $cars = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Cars query failed: " . $e->getMessage());
    $cars = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cars | Vectorental</title>
<link rel="stylesheet" href="../css/employee-dashboard.css">
<link rel="stylesheet" href="../css/employee-cars.css">
</head>
<body>

<div class="dashboard">

  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="logo">Vectorental</h2>
    <nav>
      <a href="employee-dashboard.php">Dashboard</a>
      <a href="employee-cars.php" class="active">Cars</a>
      <a href="employee-reservations.php">Reservations</a>
      <a href="employee-add-car.php">Add Car</a>
      <a href="employee-car-status.php">Car Status</a>
      <a href="employee-reports.php">Reports</a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="main-content">

    <header class="topbar">
      <span>Cars List</span>
      <a href="../../logout.php" class="logout">Logout</a>
    </header>

    <section class="content">

      <table class="cars-table">
        <thead>
          <tr>
            <th>Plate ID</th>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Color</th>
            <th>Status</th>
            <th>Office</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($cars)): ?>
          <tr>
            <td colspan="7">No cars found.</td>
          </tr>
          <?php else: ?>
          <?php foreach ($cars as $car): ?>
          <tr>
            <td><?php echo htmlspecialchars($car['plate_id']); ?></td>
            <td><?php echo htmlspecialchars($car['make']); ?></td>
            <td><?php echo htmlspecialchars($car['model']); ?></td>
            <td><?php echo htmlspecialchars($car['year']); ?></td>
            <td><?php echo htmlspecialchars($car['color']); ?></td>
            <td><?php echo htmlspecialchars($car['status']); ?></td>
            <td><?php echo htmlspecialchars($car['city']); ?></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

    </section>

  </main>
</div>

</body>
</html>