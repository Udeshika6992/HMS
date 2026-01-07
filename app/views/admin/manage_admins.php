<?php
session_start();
require_once __DIR__ . '/../../controllers/AdminController.php';

$adminController = new AdminController();
$message = "";

// ✅ Handle Add Admin
if (isset($_POST['add_admin'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($name) && !empty($email) && !empty($password)) {
        $message = $adminController->addAdmin($name, $email, $password);
    } else {
        $message = "<div class='alert alert-warning'>⚠️ All fields are required!</div>";
    }
}

// ✅ Handle Delete Admin
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $message = $adminController->deleteAdmin($id);
}

// ✅ Handle Edit Admin
if (isset($_POST['update_admin'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $message = $adminController->updateAdmin($id, $name, $email);
}

// ✅ Get all admins for table
$admins = $adminController->getAllAdmins();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admins | HMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #eef2f3;
        }
        .container {
            margin-top: 40px;
        }
        .card {
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header d-flex justify-content-between align-items-center">
            <span>👨‍💼 Manage Admins</span>
            <a href="admin_dashboard.php" class="btn btn-light btn-sm">← Back to Dashboard</a>
        </div>

        <div class="card-body">
            <?php if (!empty($message)) echo $message; ?>

            <!-- ✅ Add Admin Form -->
            <form method="POST" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="add_admin" class="btn btn-success w-100">+ Add Admin</button>
                </div>
            </form>

            <!-- ✅ Admins Table -->
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($admins)): ?>
                        <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td><?= htmlspecialchars($admin['id']); ?></td>
                                <td><?= htmlspecialchars($admin['name']); ?></td>
                                <td><?= htmlspecialchars($admin['email']); ?></td>
                                <td><?= htmlspecialchars($admin['role']); ?></td>
                                <td><?= htmlspecialchars($admin['created_at']); ?></td>
                                <td>
                                    <!-- Edit Button triggers Modal -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal<?= $admin['id'] ?>">✏️ Edit</button>
                                    
                                    <a href="?delete=<?= $admin['id']; ?>"
                                       onclick="return confirm('Are you sure you want to delete this admin?');"
                                       class="btn btn-danger btn-sm">🗑️ Delete</a>
                                </td>
                            </tr>

                            <!-- ✅ Edit Modal -->
                            <div class="modal fade" id="editModal<?= $admin['id']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Edit Admin</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $admin['id']; ?>">
                                                <div class="mb-3">
                                                    <label>Name</label>
                                                    <input type="text" name="name" class="form-control"
                                                           value="<?= htmlspecialchars($admin['name']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                           value="<?= htmlspecialchars($admin['email']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="update_admin" class="btn btn-primary">
                                                    💾 Save Changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No admins found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
