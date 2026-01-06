<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once '../../models/User.php';
$userModel = new User();

// Handle messages
$msg = "";

// ✅ CREATE new admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = md5($_POST['password']);
    
    // Check existing email
    $existing = $userModel->findUserByEmail($email);
    if ($existing) {
        $msg = "<div class='alert alert-warning'>⚠️ Email already exists!</div>";
    } else {
        $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'admin')";
        $stmt = $userModel->conn->prepare($query);
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);
        $msg = "<div class='alert alert-success'>✅ Admin added successfully!</div>";
    }
}

// ✅ UPDATE admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_admin'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $query = "UPDATE users SET name = :name, email = :email WHERE id = :id AND role = 'admin'";
    $stmt = $userModel->conn->prepare($query);
    $stmt->execute([':name' => $name, ':email' => $email, ':id' => $id]);
    $msg = "<div class='alert alert-info'>✏️ Admin updated successfully!</div>";
}

// ✅ DELETE admin
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM users WHERE id = :id AND role = 'admin'";
    $stmt = $userModel->conn->prepare($query);
    $stmt->execute([':id' => $id]);
    $msg = "<div class='alert alert-danger'>🗑️ Admin deleted successfully!</div>";
}

// ✅ Fetch all admins
$query = "SELECT * FROM users WHERE role = 'admin' ORDER BY id DESC";
$stmt = $userModel->conn->prepare($query);
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Admins - HMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../../public/css/style.css" rel="stylesheet">
</head>
<body>

<div class="dashboard-container">
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>HMS Admin</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="manage_admins.php" class="active">🧑‍💼 Manage Admins</a>
    <a href="../auth/logout.php" class="btn btn-danger w-100 mt-4">Logout</a>
  </div>

  <!-- Main content -->
  <div class="main-content">
    <div class="container">
      <h3 class="mb-4">🧑‍💼 Manage Admin Accounts</h3>


      <?php if ($msg) echo $msg; ?>

      <!-- Add New Admin -->
      <div class="card p-4 mb-4 shadow-sm">
        <h5>Add New Admin</h5>
        <form method="POST">
          <div class="row">
            <div class="col-md-3 mb-3">
              <input type="text" name="name" class="form-control" placeholder="Full Name" required>
            </div>
            <div class="col-md-3 mb-3">
              <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-3 mb-3">
              <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="col-md-3 mb-3">
              <button type="submit" name="add_admin" class="btn btn-primary w-100">Add Admin</button>
            </div>
          </div>
        </form>
      </div>

      <!-- Admins Table -->
<div class="card p-4 shadow-sm">
  <h5>All Admins</h5>
  <table class="table table-striped table-bordered mt-3">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($admins as $index => $a): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= htmlspecialchars($a['name']) ?></td>
          <td><?= htmlspecialchars($a['email']) ?></td>
          <td><?= $a['created_at'] ?></td>
          <td>
            <!-- Button triggers modal -->
            <button class="btn btn-sm btn-info text-white"
                    data-bs-toggle="modal"
                    data-bs-target="#editModal<?= $a['id'] ?>">Edit</button>

            <?php if ($a['id'] != $_SESSION['user']['id']): ?>
              <a href="?delete=<?= $a['id'] ?>"
                 onclick="return confirm('Are you sure?')"
                 class="btn btn-sm btn-danger">Delete</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- ✅ Back Button Under Table -->
  <div class="mt-3 text-end">
    <a href="dashboard.php" class="btn btn-secondary">
      ← Back to Dashboard
    </a>
  </div>
</div>

<!-- ✅ All Modals (Moved OUTSIDE the table) -->
<?php foreach ($admins as $a): ?>
<div class="modal fade" id="editModal<?= $a['id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="manage_admins.php">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Edit Admin</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="<?= $a['id'] ?>">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name"
                   value="<?= htmlspecialchars($a['name']) ?>"
                   class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email"
                   value="<?= htmlspecialchars($a['email']) ?>"
                   class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="update_admin" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
