<?php
/**
 * Employee Car Status Management
 * Allows updating car status and pricing.
 */

include '../../auth_guard.php';
require_once '../../db_connect.php';

// Handle updates
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $plate_ids = $_POST['plate_id'] ?? [];
    $statuses = $_POST['status'] ?? [];

    try {
        $pdo->beginTransaction();
        foreach ($plate_ids as $index => $plate_id) {
            $status = $statuses[$index] ?? '';

            if ($status) {
                $stmt = $pdo->prepare("UPDATE cars SET status = ? WHERE plate_id = ?");
                $stmt->execute([$status, $plate_id]);
            }
        }
        $pdo->commit();
        $message = 'Updates saved successfully.';
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Update failed: " . $e->getMessage());
        $message = 'Error saving updates.';
    }
}

// Fetch cars with specs and offices
try {
    $stmt = $pdo->query("
        SELECT c.plate_id, cs.make, cs.model, o.city, c.status
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
<title>Car Status | Vectorental</title>
<link rel="stylesheet" href="../css/employee-dashboard.css">
<link rel="stylesheet" href="../css/employee-car-status.css">
</head>
<body>
<div class="dashboard">

  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="logo">Vectorental</h2>
    <nav>
      <a href="employee-dashboard.php">Dashboard</a>
      <a href="employee-cars.php">Cars</a>
      <a href="employee-reservations.php">Reservations</a>
      <a href="employee-add-car.php">Add Car</a>
      <a href="employee-car-status.php" class="active">Car Status</a>
      <a href="employee-reports.php">Reports</a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="main-content">
    <header class="topbar">
      <span>Car Status</span>
      <a href="../../logout.php" class="logout">Logout</a>
    </header>

    <section class="content">
      <h2>Manage Car Status</h2>

      <?php if (!empty($message)): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>

      <form method="post">
        <table class="cars-table">
          <thead>
            <tr>
              <th>Plate</th>
              <th>Model</th>
              <th>Office</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cars as $car): ?>
            <tr>
              <td><?php echo htmlspecialchars($car['plate_id']); ?>
                <input type="hidden" name="plate_id[]" value="<?php echo htmlspecialchars($car['plate_id']); ?>">
              </td>
              <td><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></td>
              <td><?php echo htmlspecialchars($car['city']); ?></td>
              <td>
                <select name="status[]" class="status-select">
                  <option value="Active" <?php echo $car['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                  <option value="Rented" <?php echo $car['status'] === 'Rented' ? 'selected' : ''; ?>>Rented</option>
                  <option value="Maintenance" <?php echo $car['status'] === 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                  <option value="Out of Service" <?php echo $car['status'] === 'Out of Service' ? 'selected' : ''; ?>>Out of Service</option>
                </select>
              </td>
              <td><button type="submit" name="update" value="1" class="save-btn">Save</button></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </form>
    </section>

  </main>
</div>

</body>
</html>