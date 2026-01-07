<?php
session_start();

// ✅ Only admins can access this page
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

require_once __DIR__ . '/../../../app/controllers/AppointmentController.php';
require_once __DIR__ . '/../../../app/controllers/PatientController.php';
require_once __DIR__ . '/../../../app/controllers/DoctorController.php';

$appointmentController = new AppointmentController();
$patientController = new PatientController();
$doctorController = new DoctorController();

$message = "";

// ✅ Add Appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_appointment'])) {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $description = $_POST['description'];
    $message = $appointmentController->addAppointment($patient_id, $doctor_id, $appointment_date, $appointment_time, $description);
}

// ✅ Update Appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_appointment'])) {
    $id = $_POST['id'];
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $description = $_POST['description'];
    $message = $appointmentController->updateAppointment($id, $patient_id, $doctor_id, $appointment_date, $appointment_time, $description);
}

// ✅ Delete Appointment
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $message = $appointmentController->deleteAppointment($id);
}

// ✅ Search / Filter Appointments
if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    $appointments = $appointmentController->filterAppointments($searchTerm);
} else {
    $appointments = $appointmentController->getAllAppointments();
}

// ✅ Fetch Patients and Doctors for Dropdowns
$patients = $patientController->getAllPatients();
$doctors = $doctorController->getAllDoctors();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Appointments | HMS</title>
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
            <span>📅 Manage Appointments</span>
            <a href="admin_dashboard.php" class="btn btn-light btn-sm">⬅ Back to Dashboard</a>
        </div>

        <div class="card-body">
            <!-- Messages -->
            <?php if (!empty($message)) echo $message; ?>

            <!-- Add Appointment Form -->
            <form method="POST" class="row g-3 mb-4">
                <div class="col-md-3">
                    <select name="patient_id" class="form-select" required>
                        <option value="">Select Patient</option>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?= $patient['id']; ?>"><?= htmlspecialchars($patient['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="doctor_id" class="form-select" required>
                        <option value="">Select Doctor</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= $doctor['id']; ?>"><?= htmlspecialchars($doctor['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="date" name="appointment_date" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <input type="time" name="appointment_time" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <button type="submit" name="add_appointment" class="btn btn-add w-100">➕ Add</button>
                </div>

                <div class="col-12 mt-2">
                    <textarea name="description" class="form-control" placeholder="Description (optional)" rows="2"></textarea>
                </div>
            </form>

            <!-- Search Bar -->
            <form method="POST" class="mb-3">
                <div class="input-group">
                    <input type="text" name="searchTerm" class="form-control" placeholder="Search by doctor, patient, or date...">
                    <button class="btn btn-primary" name="search">🔍 Search</button>
                </div>
            </form>

            <!-- Appointments Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($appointments)): ?>
                            <?php foreach ($appointments as $appt): ?>
                                <tr>
                                    <td><?= htmlspecialchars($appt['id']); ?></td>
                                    <td><?= htmlspecialchars($appt['patient_name']); ?></td>
                                    <td><?= htmlspecialchars($appt['doctor_name']); ?></td>
                                    <td><?= htmlspecialchars($appt['appointment_date']); ?></td>
                                    <td><?= htmlspecialchars($appt['appointment_time']); ?></td>
                                    <td><?= htmlspecialchars($appt['description']); ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal<?= $appt['id']; ?>">✏️ Edit</button>

                                        <!-- Delete Button -->
                                        <a href="?delete=<?= $appt['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this appointment?');">
                                            🗑️ Delete
                                        </a>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal<?= $appt['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Edit Appointment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?= $appt['id']; ?>">
                                                    <div class="mb-3">
                                                        <label>Patient</label>
                                                        <select name="patient_id" class="form-select" required>
                                                            <?php foreach ($patients as $patient): ?>
                                                                <option value="<?= $patient['id']; ?>" 
                                                                    <?= ($appt['patient_name'] === $patient['name']) ? 'selected' : ''; ?>>
                                                                    <?= htmlspecialchars($patient['name']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Doctor</label>
                                                        <select name="doctor_id" class="form-select" required>
                                                            <?php foreach ($doctors as $doctor): ?>
                                                                <option value="<?= $doctor['id']; ?>" 
                                                                    <?= ($appt['doctor_name'] === $doctor['name']) ? 'selected' : ''; ?>>
                                                                    <?= htmlspecialchars($doctor['name']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Date</label>
                                                        <input type="date" name="appointment_date" value="<?= $appt['appointment_date']; ?>" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Time</label>
                                                        <input type="time" name="appointment_time" value="<?= $appt['appointment_time']; ?>" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Description</label>
                                                        <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($appt['description']); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="update_appointment" class="btn btn-primary">💾 Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-muted">No appointments found.</td></tr>
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
