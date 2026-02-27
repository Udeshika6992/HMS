<?php
/**
 * Doctor Schedule View
 * Displays doctor's working schedule and availability
 */
$baseUrl = BASE_URL;
// Suppress warnings if variables aren't set in IDE
/* @var string $BASE_URL */
/* @var array $doctor */
/* @var array $schedule */
?>


<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Create Department</h1>
        <p class="lead">Add a new hospital department</p>
    </div>
</section>

<!-- Create Department Form -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Department Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>admin/departments/create">
                        <div class="mb-3">
                            <label for="department_name" class="form-label">Department Name</label>
                            <input type="text" class="form-control" id="department_name" name="department_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="floor_number" class="form-label">Floor Number</label>
                                <input type="text" class="form-control" id="floor_number" name="floor_number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="extension_number" class="form-label">Extension Number</label>
                                <input type="text" class="form-control" id="extension_number" name="extension_number">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="head_doctor_id" class="form-label">Head of Department</label>
                            <select class="form-select" id="head_doctor_id" name="head_doctor_id">
                                <option value="">-- Select Head Doctor --</option>
                                <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?php echo $doctor['id']; ?>">
                                        Dr. <?php echo htmlspecialchars($doctor['full_name']); ?> (<?php echo htmlspecialchars($doctor['specialization'] ?? 'General'); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="text-end mt-4">
                            <a href="<?php echo BASE_URL; ?>admin/departments" class="btn btn-secondary me-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Department
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>