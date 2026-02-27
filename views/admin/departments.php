<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Department Management</h1>
        <p class="lead">Manage hospital departments</p>
    </div>
</section>

<!-- Add Department Button -->
<section class="container mb-4">
    <div class="text-end">
        <a href="<?php echo BASE_URL; ?>admin/departments/create" class="btn btn-success">
            <i class="fas fa-plus"></i> Add New Department
        </a>
    </div>
</section>

<!-- Departments List -->
<section class="container mb-5">
    <div class="row">
        <?php if (empty($departments)): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-building fa-4x text-muted mb-3"></i>
                        <h4>No departments found</h4>
                        <a href="<?php echo BASE_URL; ?>admin/departments/create" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> Add First Department
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($departments as $dept): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?php echo htmlspecialchars($dept['department_name']); ?></h5>
                            <span class="badge bg-light text-dark">Floor <?php echo htmlspecialchars($dept['floor_number'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?php echo htmlspecialchars($dept['description'] ?? 'No description'); ?></p>
                            
                            <div class="mb-2">
                                <i class="fas fa-phone text-primary me-2"></i> Ext: <?php echo htmlspecialchars($dept['extension_number'] ?? 'N/A'); ?>
                            </div>
                            
                            <div class="mb-3">
                                <i class="fas fa-user-md text-primary me-2"></i> Head of Department:
                                <?php if ($dept['head_doctor_name']): ?>
                                    <strong>Dr. <?php echo htmlspecialchars($dept['head_doctor_name']); ?></strong>
                                <?php else: ?>
                                    <em class="text-muted">Not assigned</em>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-<?php echo $dept['is_active'] ? 'success' : 'secondary'; ?>">
                                    <?php echo $dept['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                                <div>
                                    <a href="<?php echo BASE_URL; ?>admin/departments/edit/<?php echo $dept['id']; ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <?php if ($dept['is_active']): ?>
                                        <form method="POST" action="<?php echo BASE_URL; ?>admin/departments/delete/<?php echo $dept['id']; ?>" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this department?');">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>