</html>

<?php
/**
 * Employee Reservations
 * Displays all reservations in a table with JOIN queries.
 */

include_once '../../auth_guard.php';
require_once '../../db_connect.php';

try {
    // Fetch all reservations with joins
    $stmt = $pdo->query("
      SELECT r.reservation_id, CONCAT(cust.first_name, ' ', cust.last_name) AS customer_name, cust.phone_number, car.plate_id, cs.make, cs.model,
           o1.city AS pickup_city, o2.city AS return_city, r.pickup_date, r.return_date, r.reservation_status
      FROM reservations r
      JOIN customers cust ON r.customer_id = cust.customer_id
      JOIN cars car ON r.car_id = car.car_id
      JOIN car_specs cs ON car.spec_id = cs.spec_id
      JOIN offices o1 ON r.pickup_office_id = o1.office_id
      JOIN offices o2 ON r.return_office_id = o2.office_id
      ORDER BY r.pickup_date DESC
    ");
    $reservations = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Reservations query failed: " . $e->getMessage());
    $reservations = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reservations | Vectorental</title>
<link rel="stylesheet" href="../css/employee-dashboard.css">
<link rel="stylesheet" href="../css/employee-reservations.css">
</head>
<body>

<div class="dashboard">

  <aside class="sidebar">
    <h2 class="logo">Vectorental</h2>
    <nav>
      <a href="employee-dashboard.php">Dashboard</a>
      <a href="employee-cars.php">Cars</a>
      <a href="employee-reservations.php" class="active">Reservations</a>
      <a href="employee-add-car.php">Add Car</a>
      <a href="employee-car-status.php">Car Status</a>
      <a href="employee-reports.php">Reports</a>
    </nav>
  </aside>

  <main class="main-content">

    <header class="topbar">
      <span>Reservations</span>
      <a href="../../logout.php" class="logout">Logout</a>
    </header>

    <section class="content">
      <h2>All Reservations</h2>

      <table>
        <thead>
          <tr>
            <th>Reservation ID</th>
            <th>Customer Name</th>
            <th>Phone</th>
            <th>Car</th>
            <th>Plate ID</th>
            <th>Pickup Location</th>
            <th>Return Location</th>
            <th>Pickup Date</th>
            <th>Return Date</th>
            <th>Status</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($reservations as $res): ?>
          <tr>
            <td><?php echo htmlspecialchars($res['reservation_id']); ?></td>
            <td><?php echo htmlspecialchars($res['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($res['phone_number']); ?></td>
            <td><?php echo htmlspecialchars($res['make'] . ' ' . $res['model']); ?></td>
            <td><?php echo htmlspecialchars($res['plate_id']); ?></td>
            <td><?php echo htmlspecialchars($res['pickup_city']); ?></td>
            <td><?php echo htmlspecialchars($res['return_city']); ?></td>
            <td><?php echo htmlspecialchars($res['pickup_date']); ?></td>
            <td><?php echo htmlspecialchars($res['return_date']); ?></td>
            <td><span class="status <?php
              $statusClass = 'pending'; // default
              if (isset($res['reservation_status']) && in_array($res['reservation_status'], ['Active', 'Confirmed'])) { $statusClass = 'confirmed'; }
              elseif (isset($res['reservation_status']) && $res['reservation_status'] === 'Completed') { $statusClass = 'completed'; }
              elseif (isset($res['reservation_status']) && $res['reservation_status'] === 'Cancelled') { $statusClass = 'out'; }
              echo $statusClass;
            ?>"><?php echo isset($res['reservation_status']) ? htmlspecialchars($res['reservation_status']) : 'N/A'; ?></span></td>
          </html>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

  </main>
</div>

</body>
</html>