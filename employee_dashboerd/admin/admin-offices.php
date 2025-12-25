<?php
/**
 * Admin Offices Management
 * Displays and manages offices.
 */

include '../../auth_guard.php';
require_once '../../db_connect.php';

// Check if admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../employee/employee-dashboard.php");
    exit();
}

// Handle add office
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_office'])) {
    $city = trim($_POST['city'] ?? '');
    $status = $_POST['status'] ?? 'Active';

    if (empty($city)) {
        $message = 'City is required.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO offices (city, status) VALUES (?, ?)");
            $stmt->execute([$city, $status]);
            $message = 'Office added successfully.';
        } catch (PDOException $e) {
            error_log("Add office failed: " . $e->getMessage());
            $message = 'Error adding office.';
        }
    }
}

// Handle edit office
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_office'])) {
    $office_id = $_POST['office_id'] ?? '';
    $city = trim($_POST['city'] ?? '');
    $status = $_POST['status'] ?? 'Active';

    if (empty($office_id) || empty($city)) {
        $message = 'Office ID and city are required.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE offices SET city = ?, status = ? WHERE office_id = ?");
            $stmt->execute([$city, $status, $office_id]);
            $message = 'Office updated successfully.';
        } catch (PDOException $e) {
            error_log("Update office failed: " . $e->getMessage());
            $message = 'Error updating office.';
        }
    }
}

// Handle delete office
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_office'])) {
    $office_id = $_POST['office_id'] ?? '';

    if (empty($office_id)) {
        $message = 'Office ID missing.';
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM offices WHERE office_id = ?");
            $stmt->execute([$office_id]);
            $message = 'Office deleted successfully.';
        } catch (PDOException $e) {
            error_log("Delete office failed: " . $e->getMessage());
            $message = 'Error deleting office.';
        }
    }
}

// Fetch offices
try {
    $stmt = $pdo->query("SELECT office_id, city, status FROM offices ORDER BY office_id");
    $offices = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Offices query failed: " . $e->getMessage());
    $offices = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Vectorental | Offices</title>
<link rel="stylesheet" href="../css/admin-dashboard.css">
<link rel="stylesheet" href="../css/admin-offices.css">
</head>
<body>

<div class="dashboard">

  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="logo">Vectorental</h2>
    <nav>
      <a href="admin-dashboard.php">Dashboard</a>
      <a href="admin-employees.php">Employees</a>
      <a href="admin-offices.php" class="active">Offices</a>
      <a href="admin-reports.php">Reports</a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="main-content">

    <header class="topbar">
      <span>Offices Management</span>
      <a href="../../logout.php" class="logout">Logout</a>
    </header>

    <section class="content">

      <div class="header-row">
        <h2>All Offices</h2>
        <button class="add-btn" onclick="document.getElementById('addForm').style.display='block'">+ Add Office</button>
      </div>

      <?php if (!empty($message)): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>

      <table class="offices-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>City</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($offices as $office): ?>
          <tr>
            <td><?php echo htmlspecialchars($office['office_id']); ?></td>
            <td><?php echo htmlspecialchars($office['city']); ?></td>
            <td><span class="status <?php echo strtolower($office['status']); ?>"><?php echo htmlspecialchars($office['status']); ?></span></td>
            <td>
              <button class="action-btn edit" onclick="editOffice(<?php echo htmlspecialchars($office['office_id']); ?>, '<?php echo htmlspecialchars($office['city']); ?>', '<?php echo htmlspecialchars($office['status']); ?>')">Edit</button>
              <button class="action-btn delete" onclick="deleteOffice(<?php echo htmlspecialchars($office['office_id']); ?>)">Delete</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Add Office Modal -->
      <div id="addForm" class="modal">
        <div class="modal-content">
          <span class="close" onclick="document.getElementById('addForm').style.display='none'">&times;</span>
          <h2>Add New Office</h2>
          <form method="post">
            <input type="hidden" name="add_office" value="1">
            <div class="form-group">
              <label>City</label>
              <input type="text" name="city" placeholder="City" required>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
            <button type="submit" class="btn">Add Office</button>
          </form>
        </div>
      </div>

      <!-- Edit Office Modal -->
      <div id="editForm" class="modal">
        <div class="modal-content">
          <span class="close" onclick="document.getElementById('editForm').style.display='none'">&times;</span>
          <h2>Edit Office</h2>
          <form method="post">
            <input type="hidden" name="edit_office" value="1">
            <input type="hidden" id="edit_office_id" name="office_id">
            <div class="form-group">
              <label>City</label>
              <input type="text" id="edit_city" name="city" placeholder="City" required>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select id="edit_status" name="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
            <button type="submit" class="btn">Update Office</button>
          </form>
        </div>
      </div>

      <!-- Delete Office Form -->
      <form id="deleteForm" method="post" style="display:none;">
        <input type="hidden" name="delete_office" value="1">
        <input type="hidden" id="delete_office_id" name="office_id">
      </form>

    </section>

  </main>
</div>

<script>
// Get the modals
var addModal = document.getElementById('addForm');
var editModal = document.getElementById('editForm');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == addModal) {
    addModal.style.display = "none";
  }
  if (event.target == editModal) {
    editModal.style.display = "none";
  }
}

function editOffice(id, city, status) {
  document.getElementById('edit_office_id').value = id;
  document.getElementById('edit_city').value = city;
  document.getElementById('edit_status').value = status;
  editModal.style.display = 'block';
}

function deleteOffice(id) {
  if (confirm('Are you sure you want to delete this office?')) {
    document.getElementById('delete_office_id').value = id;
    document.getElementById('deleteForm').submit();
  }
}
</script>

</body>
</html>