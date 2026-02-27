<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Patient Management</h1>
        <p class="lead">View and manage all patients</p>
    </div>
</section>

<!-- Search -->
<section class="container mb-4">
    <div class="card">
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Search patients by name, email, or phone..." 
                               value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="<?php echo BASE_URL; ?>admin/users/create" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add New Patient
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Patients Table -->
<section class="container mb-5">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Contact</th>
                            <th>Blood Group</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($patients)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">No patients found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($patients as $patient): ?>
                                <tr>
                                    <td><?php echo $patient['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo UPLOAD_URL; ?>profiles/<?php echo $patient['profile_image'] ?? 'default-avatar.png'; ?>" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            <div>
                                                <strong><?php echo htmlspecialchars($patient['full_name']); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($patient['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-phone"></i> <?php echo htmlspecialchars($patient['phone'] ?? 'N/A'); ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger"><?php echo htmlspecialchars($patient['blood_group'] ?? 'N/A'); ?></span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($patient['created_at'])); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>admin/view-patient/<?php echo $patient['id']; ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>admin/users/edit/<?php echo $patient['user_id']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
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