<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">User Management</h1>
        <p class="lead">Manage system users</p>
    </div>
</section>

<!-- Search and Add -->
<section class="container mb-4">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <form method="GET" class="d-flex">
                        <input type="text" class="form-control me-2" name="search" 
                               placeholder="Search users..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <a href="<?php echo BASE_URL; ?>admin/users/create" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add New User
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Users Table -->
<section class="container mb-5">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">No users found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $user['role'] == 'admin' ? 'danger' : 
                                                ($user['role'] == 'doctor' ? 'info' : 'success'); 
                                        ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
                                            <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>admin/users/edit/<?php echo $user['id']; ?>" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <form method="POST" action="<?php echo BASE_URL; ?>admin/users/toggle-status/<?php echo $user['id']; ?>" 
                                                  style="display: inline;">
                                                <button type="submit" class="btn btn-sm btn-<?php echo $user['is_active'] ? 'warning' : 'success'; ?>"
                                                        onclick="return confirm('Toggle user status?')">
                                                    <i class="fas fa-<?php echo $user['is_active'] ? 'ban' : 'check'; ?>"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo BASE_URL; ?>admin/users/delete/<?php echo $user['id']; ?>" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['has_previous']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $pagination['previous_page']; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <li class="page-item <?php echo $i == $pagination['page'] ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['has_next']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $pagination['next_page']; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                    Next
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</section>