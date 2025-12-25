<?php
/**
 * Add Car Handler
 * Handles form submission to add new car to database.
 */

include '../../auth_guard.php';
require_once '../../db_connect.php';

// Initialize variables
$message = '';
$messageType = ''; // 'success' or 'error'
$specs = [];
$offices = [];

// Fetch car specs for dropdown
try {
    $stmt = $pdo->query("SELECT spec_id, make, model, year FROM car_specs ORDER BY make, model");
    $specs = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Specs query failed: " . $e->getMessage());
}

// Fetch offices for dropdown
try {
    $stmt = $pdo->query("SELECT office_id, city FROM offices ORDER BY city");
    $offices = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Offices query failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plate_id = trim($_POST['plate_id'] ?? '');
    $spec_id = $_POST['spec_id'] ?? '';
    $office_id = $_POST['office_id'] ?? '';
    $color = trim($_POST['color'] ?? '');
    $odometer = $_POST['odometer'] ?? '';

    // Validation
    if (empty($plate_id) || empty($spec_id) || empty($office_id) || empty($color) || $odometer === '') {
        $message = 'All fields are required.';
        $messageType = 'error';
    } else {
        // Check if plate_id exists and insert new car
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM cars WHERE plate_id = ?");
            $stmt->execute([$plate_id]);
            if ($stmt->fetchColumn() > 0) {
                $message = 'Plate ID already exists.';
                $messageType = 'error';
            } else {
                // Insert new car
                $stmt = $pdo->prepare("INSERT INTO cars (plate_id, status, spec_id, current_office_id, color, current_odometer) VALUES (?, 'Active', ?, ?, ?, ?)");
                $stmt->execute([$plate_id, $spec_id, $office_id, $color, $odometer]);
                $message = 'Car added successfully.';
                $messageType = 'success';
            }
        } catch (PDOException $e) {
            error_log("Database operation failed: " . $e->getMessage());
            $message = 'Error adding car. Please try again.';
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Car | Vectorental</title>
<link rel="stylesheet" href="../css/employee-dashboard.css">
<link rel="stylesheet" href="../css/employee-add-car.css">
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
      <a href="employee-add-car.php" class="active">Add Car</a>
      <a href="employee-car-status.php">Car Status</a>
      <a href="employee-reports.php">Reports</a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="main-content">

    <header class="topbar">
      <span>Add New Car</span>
      <a href="../../logout.php" class="logout">Logout</a>
    </header>

    <?php if (!empty($message)): ?>
    <div class="alert <?php echo $messageType ?: 'success'; ?>" style="width: 520px; padding: 15px; border-radius: 10px; margin: 20px auto; font-weight: 500; text-align: center; background: <?php echo $messageType === 'error' ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 'linear-gradient(135deg, #10b981, #059669)'; ?>; color: white; border: 1px solid <?php echo $messageType === 'error' ? '#b91c1c' : '#047857'; ?>; box-shadow: 0 4px 6px <?php echo $messageType === 'error' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(16, 185, 129, 0.1)'; ?>;">
      <?php echo htmlspecialchars($message); ?>
    </div>
    <?php endif; ?>

    <section class="content">

      <form class="car-form" method="post">

        <div class="form-group">
          <label>Plate ID</label>
          <input type="text" name="plate_id" placeholder="CAI-123" required>
        </div>

        <div class="form-group">
          <label>Car Model</label>
          <select name="spec_id" required>
            <option value="">Select Model</option>
            <?php foreach ($specs as $spec): ?>
            <option value="<?php echo htmlspecialchars($spec['spec_id']); ?>">
              <?php echo htmlspecialchars($spec['make'] . ' ' . $spec['model'] . ' (' . $spec['year'] . ')'); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label>Office</label>
          <select name="office_id" required>
            <option value="">Select Office</option>
            <?php foreach ($offices as $office): ?>
            <option value="<?php echo htmlspecialchars($office['office_id']); ?>">
              <?php echo htmlspecialchars($office['city']); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label>Color</label>
          <input type="text" name="color" placeholder="Red" required>
        </div>

        <div class="form-group">
          <label>Odometer (km)</label>
          <input type="number" name="odometer" placeholder="0" required>
        </div>

        <button type="submit" class="btn">Add Car</button>

      </form>

    </section>

  </main>
</div>

</body>
</html>