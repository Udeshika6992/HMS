<?php
session_start();

// ✅ Restrict access to admin only
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// ✅ Include Department Controller
require_once __DIR__ . '/../../../app/controllers/DepartmentController.php';
$controller = new DepartmentController();

$msg = "";

// ✅ Add Department
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_department'])) {
    $name = trim($_POST['department_name']);
    if (!empty($name)) {
        $added = $controller->addDepartment($name);
        $msg = $added 
            ? "<div class='alert alert-success'>✅ Department added successfully!</div>"
            : "<div class='alert alert-warning'>⚠️ Department already exists!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>❌ Please enter a department name!</div>";
    }
}

// ✅ Update Department
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_department'])) {
    $id = $_POST['id'];
    $name = trim($_POST['department_name']);
    if (!empty($id) && !empty($name)) {
        $updated = $controller->updateDepartment($id, $name);
        $msg = $updated 
            ? "<div class='alert alert-info'>✏️ Department updated successfully!</div>"
            : "<div class='alert alert-danger'>❌ Failed to update department!</div>";
    }
}

// ✅ Delete Department
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleted = $controller->deleteDepartment($id);
    $msg = $deleted 
        ? "<div class='alert alert-danger'>🗑️ Department deleted successfully!</div>"
        : "<div class='alert alert-warning'>⚠️ Failed to delete department!</div>";
}

// ✅ Fetch all departments
$departments = $controller->getAllDepartments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments | HMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #eef3ff;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .btn-add {
            background-color: #28a745;
            color: white;
        }
        .btn-add:hover {
            background-color: #218838;
        }
        .back-btn {
            float: right;
        }
        .card {
            border-radius: 10px;
        }
        .modal-header {
            background-color: #0dcaf0;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">🏥 Manage Departments</h4>
            <a href="admin_dashboard.php" class="btn btn-light btn-sm">⬅ Back to Dashboard</a>
        </div>
        <div class="card-body">

            <!-- Alert Message -->
            <?= $msg ?>

            <!-- Add Department Form -->
            <form method="POST" class="row g-3 mb-4">
                <div class="col-md-8">
                    <input type="text" name="department_name" class="form-control" placeholder="Enter department name" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="add_department" class="btn btn-add w-100">➕ Add Department</button>
                </div>
            </form>

            <!-- Departments Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Department Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($departments)): ?>
                            <?php foreach ($departments as $dept): ?>
                                <tr>
                                    <td><?= htmlspecialchars($dept['id']) ?></td>
                                    <td><?= htmlspecialchars($dept['department_name']) ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $dept['id'] ?>">
                                            ✏ Edit
                                        </button>

                                        <!-- Delete Button -->
                                        <a href="?delete=<?= $dept['id'] ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this department?');">
                                            🗑 Delete
                                        </a>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal<?= $dept['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Department</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?= $dept['id'] ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Department Name</label>
                                                        <input type="text" name="department_name" class="form-control" value="<?= htmlspecialchars($dept['department_name']) ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="update_department" class="btn btn-primary">💾 Save Changes</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">No departments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
