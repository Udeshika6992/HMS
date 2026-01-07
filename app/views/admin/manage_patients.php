<?php
session_start();

// ✅ Restrict access to admin only
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

require_once __DIR__ . '/../../../app/controllers/PatientController.php';
$controller = new PatientController();

$msg = "";

// ✅ Add Patient
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_patient'])) {
    $msg = $controller->addPatient(
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['gender'],
        $_POST['age'],
        $_POST['address'],
        $_POST['disease']
    );
}

// ✅ Update Patient
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_patient'])) {
    $msg = $controller->updatePatient(
        $_POST['id'],
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['gender'],
        $_POST['age'],
        $_POST['address'],
        $_POST['disease']
    );
}

// ✅ Delete Patient
if (isset($_GET['delete'])) {
    $msg = $controller->deletePatient($_GET['delete']);
}

// ✅ Search Patients
$patients = [];
if (isset($_GET['search']) && !empty($_GET['keyword'])) {
    $patients = $controller->searchPatients($_GET['keyword']);
} else {
    $patients = $controller->getAllPatients();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients | HMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #eef3ff; font-family: 'Segoe UI', sans-serif; }
        .container { margin-top: 50px; }
        .table th { background-color: #007bff; color: white; }
        .btn-add { background-color: #28a745; color: white; }
        .btn-add:hover { background-color: #218838; }
        .modal-header { background: #0dcaf0; color: white; }
        .back-btn { float: right; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">🏥 Manage Patients</h4>
            <a href="admin_dashboard.php" class="btn btn-light btn-sm">⬅ Back to Dashboard</a>
        </div>

        <div class="card-body">
            <?= $msg ? "<div class='alert alert-info'>$msg</div>" : "" ?>

            <!-- Add Patient Form -->
            <form method="POST" class="row g-3 mb-4">
                <div class="col-md-4"><input type="text" name="name" class="form-control" placeholder="Full Name" required></div>
                <div class="col-md-4"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                <div class="col-md-4"><input type="text" name="phone" class="form-control" placeholder="Phone" required></div>

                <div class="col-md-3">
                    <select name="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-2"><input type="number" name="age" class="form-control" placeholder="Age"></div>
                <div class="col-md-4"><input type="text" name="address" class="form-control" placeholder="Address"></div>
                <div class="col-md-3"><input type="text" name="disease" class="form-control" placeholder="Disease/Condition"></div>

                <div class="col-12">
                    <button type="submit" name="add_patient" class="btn btn-add w-100">➕ Add Patient</button>
                </div>
            </form>

            <!-- Search Bar -->
            <form method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Search by ID, Name or Email">
                    <button type="submit" name="search" class="btn btn-outline-primary">🔍 Search</button>
                    <a href="manage_patients.php" class="btn btn-outline-secondary">⟳ Reset</a>
                </div>
            </form>

            <!-- Patient Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>Disease</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($patients)): ?>
                            <?php foreach ($patients as $pat): ?>
                                <tr>
                                    <td><?= $pat['id'] ?></td>
                                    <td><?= htmlspecialchars($pat['patient_id']) ?></td>
                                    <td><?= htmlspecialchars($pat['name']) ?></td>
                                    <td><?= htmlspecialchars($pat['email']) ?></td>
                                    <td><?= htmlspecialchars($pat['phone']) ?></td>
                                    <td><?= htmlspecialchars($pat['gender']) ?></td>
                                    <td><?= htmlspecialchars($pat['age']) ?></td>
                                    <td><?= htmlspecialchars($pat['disease']) ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $pat['id'] ?>">✏ Edit</button>
                                        <a href="?delete=<?= $pat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">🗑 Delete</a>
                                        <button class="btn btn-info btn-sm" onclick="showProgress('<?= $pat['patient_id'] ?>')">📈 Progress</button>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal<?= $pat['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Patient</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?= $pat['id'] ?>">
                                                    <input type="text" name="name" value="<?= htmlspecialchars($pat['name']) ?>" class="form-control mb-2" required>
                                                    <input type="email" name="email" value="<?= htmlspecialchars($pat['email']) ?>" class="form-control mb-2" required>
                                                    <input type="text" name="phone" value="<?= htmlspecialchars($pat['phone']) ?>" class="form-control mb-2">
                                                    <input type="text" name="gender" value="<?= htmlspecialchars($pat['gender']) ?>" class="form-control mb-2">
                                                    <input type="number" name="age" value="<?= htmlspecialchars($pat['age']) ?>" class="form-control mb-2">
                                                    <input type="text" name="address" value="<?= htmlspecialchars($pat['address']) ?>" class="form-control mb-2">
                                                    <input type="text" name="disease" value="<?= htmlspecialchars($pat['disease']) ?>" class="form-control mb-2">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="update_patient" class="btn btn-primary">💾 Save</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-muted">No patients found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Patient Progress Chart -->
            <div class="card mt-5">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📊 Patient Progress Chart</h5>
                </div>
                <div class="card-body">
                    <canvas id="progressChart" height="100"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// 📈 Fetch patient progress using AJAX
function showProgress(patientId) {
    fetch('../../../app/api/get_progress.php?patient_id=' + patientId)
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('progressChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(row => row.date),
                    datasets: [{
                        label: 'Progress (%)',
                        data: data.map(row => row.progress_level),
                        borderColor: '#007bff',
                        fill: false,
                        tension: 0.3
                    }]
                }
            });
        })
        .catch(err => alert('Error loading patient progress.'));
}
</script>

</body>
</html>
