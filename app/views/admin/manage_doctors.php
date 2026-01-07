<?php
session_start();

// ✅ Allow only admins to access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

require_once __DIR__ . '/../../../app/controllers/DoctorController.php';
require_once __DIR__ . '/../../../app/controllers/DepartmentController.php';

$doctorController = new DoctorController();
$departmentController = new DepartmentController();

$message = "";

// ✅ Add Doctor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_doctor'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $specialization = trim($_POST['specialization']);
    $department_id = $_POST['department_id'];
    $password = trim($_POST['password']);
    $message = $doctorController->addDoctor($name, $email, $specialization, $department_id, $password);
}

// ✅ Update Doctor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_doctor'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $specialization = trim($_POST['specialization']);
    $department_id = $_POST['department_id'];
    $message = $doctorController->updateDoctor($id, $name, $email, $specialization, $department_id);
}

// ✅ Delete Doctor
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $message = $doctorController->deleteDoctor($id);
}

// ✅ Fetch all doctors and departments
$doctors = $doctorController->getAllDoctors();
$departments = $departmentController->getAllDepartments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Doctors | HMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .container { margin-top: 50px; }
        .card { box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1); }
        .header { background-color: #007bff; color: white; padding: 15px; font-size: 18px; font-weight: bold; }
        .btn-add { background-color: #28a745; color: white; }
        .btn-add:hover { background-color: #218838; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="header d-flex justify-content-between align-items-center">
            <span>👨‍⚕️ Manage Doctors</span>
            <a href="admin_dashboard.php" class="btn btn-light btn-sm">⬅ Back to Dashboard</a>
        </div>

        <div class="card-body">
            <!-- Message Section -->
            <?php if (!empty($message)) echo $message; ?>

            <!-- Add Doctor Form -->
            <form method="POST" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Doctor Name" required>
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="specialization" class="form-control" placeholder="Specialization" required>
                </div>
                <div class="col-md-2">
                    <select name="department_id" class="form-select" required>
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['id']; ?>"><?= htmlspecialchars($dept['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="col-md-12 text-end">
                    <button type="submit" name="add_doctor" class="btn btn-add">➕ Add Doctor</button>
                </div>
            </form>

            <!-- Doctor Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Specialization</th>
                            <th>Department</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($doctors)): ?>
                            <?php foreach ($doctors as $doctor): ?>
                                <tr>
                                    <td><?= htmlspecialchars($doctor['id']); ?></td>
                                    <td><?= htmlspecialchars($doctor['name']); ?></td>
                                    <td><?= htmlspecialchars($doctor['email']); ?></td>
                                    <td><?= htmlspecialchars($doctor['specialization']); ?></td>
                                    <td><?= htmlspecialchars($doctor['department_name']); ?></td>
                                    <td><?= htmlspecialchars($doctor['created_at']); ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal<?= $doctor['id']; ?>">✏️ Edit</button>

                                        <!-- Delete Button -->
                                        <a href="?delete=<?= $doctor['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this doctor?');">
                                            🗑️ Delete
                                        </a>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal<?= $doctor['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Edit Doctor</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?= $doctor['id']; ?>">
                                                    <div class="mb-3">
                                                        <label>Name</label>
                                                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($doctor['name']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($doctor['email']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Specialization</label>
                                                        <input type="text" name="specialization" class="form-control" value="<?= htmlspecialchars($doctor['specialization']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Department</label>
                                                        <select name="department_id" class="form-select" required>
                                                            <?php foreach ($departments as $dept): ?>
                                                                <option value="<?= $dept['id']; ?>" <?= ($doctor['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                                                    <?= htmlspecialchars($dept['name']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="update_doctor" class="btn btn-primary">💾 Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-muted">No doctors found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
