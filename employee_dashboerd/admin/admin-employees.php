<?php
/**
 * Admin Employees Management
 * Displays and manages employees.
 */

include '../../auth_guard.php';
require_once '../../db_connect.php';

// Check if admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../employee/employee-dashboard.php");
    exit();
}

// Handle add employee
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $office_id = $_POST['office_id'] ?? '';
    $role = $_POST['role'] ?? 'employee';
    $status = $_POST['status'] ?? 'Active';

    if (empty($username) || empty($password) || empty($office_id)) {
        $message = 'Required fields are missing.';
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO Employees (username, password_hash, role, office_id, full_name, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $role, $office_id, $name, $status]);
            $message = 'Employee added successfully.';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = 'Username already exists.';
            } else {
                error_log("Add employee failed: " . $e->getMessage());
                $message = 'Error adding employee.';
            }
        }
    }
}

// Handle delete employee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_employee'])) {
    $employee_id = $_POST['employee_id'] ?? '';

    if (empty($employee_id)) {
        $message = 'Employee ID missing.';
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM Employees WHERE employee_id = ?");
            $stmt->execute([$employee_id]);
            $message = 'Employee deleted successfully.';
        } catch (PDOException $e) {
            error_log("Delete employee failed: " . $e->getMessage());
            $message = 'Error deleting employee.';
        }
    }
}

// Handle edit employee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_employee'])) {
    $employee_id = $_POST['employee_id'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $office_id = $_POST['office_id'] ?? '';
    $role = $_POST['role'] ?? 'employee';
    $status = $_POST['status'] ?? 'Active';

    if (empty($employee_id) || empty($username) || empty($office_id)) {
        $message = 'Required fields are missing.';
    } else {
        try {
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE Employees SET username = ?, password_hash = ?, role = ?, office_id = ?, full_name = ?, status = ? WHERE employee_id = ?");
                $stmt->execute([$username, $hashedPassword, $role, $office_id, $name, $status, $employee_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE Employees SET username = ?, role = ?, office_id = ?, full_name = ?, status = ? WHERE employee_id = ?");
                $stmt->execute([$username, $role, $office_id, $name, $status, $employee_id]);
            }
            $message = 'Employee updated successfully.';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = 'Username already exists.';
            } else {
                error_log("Update employee failed: " . $e->getMessage());
                $message = 'Error updating employee.';
            }
        }
    }
}

// Fetch employees
try {
    $stmt = $pdo->query("
        SELECT e.employee_id, e.username, e.role, e.full_name, e.status, o.city, o.office_id
        FROM Employees e
        JOIN Offices o ON e.office_id = o.office_id
        ORDER BY e.employee_id
    ");
    $employees = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Employees query failed: " . $e->getMessage());
    $employees = [];
}

// Fetch offices for dropdown
$offices = [];
try {
    $stmt = $pdo->query("SELECT office_id, city FROM Offices ORDER BY city");
    $offices = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Offices query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Vectorental | Employees</title>
<link rel="stylesheet" href="../css/admin-dashboard.css">
<link rel="stylesheet" href="../css/admin-employees.css">
</head>
<body>

<div class="dashboard">

  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="logo">Vectorental</h2>
    <nav>
      <a href="admin-dashboard.php">Dashboard</a>
      <a href="admin-employees.php" class="active">Employees</a>
      <a href="admin-offices.php">Offices</a>
      <a href="admin-reports.php">Reports</a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="main-content">

    <header class="topbar">
      <span>Employees Management</span>
      <a href="../../logout.php" class="logout">Logout</a>
    </header>

    <section class="content">

      <div class="header-row">
        <h2>All Employees</h2>
        <button class="add-btn" onclick="document.getElementById('addForm').style.display='block'">+ Add Employee</button>
      </div>

      <?php if (!empty($message)): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>

      <table class="employees-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Username</th>
            <th>Role</th>
            <th>Office</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($employees as $emp): ?>
          <tr>
            <td><?php echo htmlspecialchars($emp['employee_id']); ?></td>
            <td><?php echo htmlspecialchars($emp['full_name'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($emp['username']); ?></td>
            <td><?php echo htmlspecialchars($emp['role']); ?></td>
            <td><?php echo htmlspecialchars($emp['city']); ?></td>
            <td><span class="status <?php echo strtolower($emp['status']); ?>"><?php echo htmlspecialchars($emp['status']); ?></span></td>
            <td>
              <button class="action-btn edit" onclick="editEmployee(<?php echo htmlspecialchars($emp['employee_id']); ?>, '<?php echo htmlspecialchars(addslashes($emp['full_name'] ?? '')); ?>', '<?php echo htmlspecialchars($emp['username']); ?>', '<?php echo htmlspecialchars($emp['role']); ?>', <?php echo htmlspecialchars($emp['office_id']); ?>, '<?php echo htmlspecialchars($emp['status']); ?>')">Edit</button>
              <button class="action-btn delete" onclick="deleteEmployee(<?php echo htmlspecialchars($emp['employee_id']); ?>)">Delete</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Add Employee Modal -->
      <div id="addForm" class="modal">
        <div class="modal-content">
          <span class="close" onclick="document.getElementById('addForm').style.display='none'">&times;</span>
          <h2>Add New Employee</h2>
          <form method="post">
            <input type="hidden" name="add_employee" value="1">
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" name="name" placeholder="Full Name">
            </div>
            <div class="form-group">
              <label>Username</label>
              <input type="text" name="username" required>
            </div>
            <div class="form-group">
              <label>Password</label>
              <input type="password" name="password" required>
            </div>
            <div class="form-group">
              <label>Office</label>
              <select name="office_id" required>
                <option value="">Select Office</option>
                <?php foreach ($offices as $office): ?>
                <option value="<?php echo htmlspecialchars($office['office_id']); ?>"><?php echo htmlspecialchars($office['city']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Role</label>
              <select name="role">
                <option value="employee">Employee</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
            <button type="submit" class="btn">Add Employee</button>
          </form>
        </div>
      </div>

      <!-- Edit Employee Modal -->
      <div id="editForm" class="modal">
        <div class="modal-content">
          <span class="close" onclick="document.getElementById('editForm').style.display='none'">&times;</span>
          <h2>Edit Employee</h2>
          <form method="post">
            <input type="hidden" name="edit_employee" value="1">
            <input type="hidden" id="edit_employee_id" name="employee_id">
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" id="edit_name" name="name" placeholder="Full Name">
            </div>
            <div class="form-group">
              <label>Username</label>
              <input type="text" id="edit_username" name="username" required>
            </div>
            <div class="form-group">
              <label>Password (leave blank to keep current)</label>
              <input type="password" id="edit_password" name="password">
            </div>
            <div class="form-group">
              <label>Office</label>
              <select id="edit_office_id" name="office_id" required>
                <option value="">Select Office</option>
                <?php foreach ($offices as $office): ?>
                <option value="<?php echo htmlspecialchars($office['office_id']); ?>"><?php echo htmlspecialchars($office['city']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Role</label>
              <select id="edit_role" name="role">
                <option value="employee">Employee</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select id="edit_status" name="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
            <button type="submit" class="btn">Update Employee</button>
          </form>
        </div>
      </div>

      <!-- Delete Employee Form -->
      <form id="deleteForm" method="post" style="display:none;">
        <input type="hidden" name="delete_employee" value="1">
        <input type="hidden" id="delete_employee_id" name="employee_id">
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

function editEmployee(id, full_name, username, role, office_id, status) {
  document.getElementById('edit_employee_id').value = id;
  document.getElementById('edit_name').value = full_name;
  document.getElementById('edit_username').value = username;
  document.getElementById('edit_role').value = role;
  document.getElementById('edit_office_id').value = office_id;
  document.getElementById('edit_status').value = status;
  document.getElementById('edit_password').value = ''; // Clear password
  editModal.style.display = 'block';
}

function deleteEmployee(id) {
  if (confirm('Are you sure you want to delete this employee?')) {
    document.getElementById('delete_employee_id').value = id;
    document.getElementById('deleteForm').submit();
  }
}
</script>

</body>
</html>