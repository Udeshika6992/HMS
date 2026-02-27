<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Medical History</h1>
        <p class="lead">Your complete health record at a glance</p>
    </div>
</section>

<!-- Timeline View -->
<section class="container mb-5">
    <?php if (empty($history)): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-notes-medical fa-4x text-muted mb-3"></i>
                <h4>No medical records yet</h4>
                <p class="text-muted">Your medical history will appear here after your first doctor visit.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="timeline">
            <?php 
            $currentYear = '';
            foreach ($history as $record): 
                $year = date('Y', strtotime($record['record_date']));
                if ($year != $currentYear):
                    $currentYear = $year;
            ?>
                <div class="year-divider">
                    <h3 class="text-primary"><?php echo $year; ?></h3>
                </div>
            <?php endif; ?>
            
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <div class="date-badge">
                                <span class="day"><?php echo date('d', strtotime($record['record_date'])); ?></span>
                                <span class="month"><?php echo date('M', strtotime($record['record_date'])); ?></span>
                            </div>
                            <span class="badge bg-info mt-2"><?php echo ucfirst($record['visit_type']); ?></span>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title">Dr. <?php echo $record['doctor_name']; ?></h5>
                                    <p class="text-muted"><?php echo $record['specialization']; ?></p>
                                </div>
                                <a href="<?php echo BASE_URL; ?>patient/view-medical-record/<?php echo $record['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                            
                            <?php if ($record['diagnosis']): ?>
                                <div class="mt-2">
                                    <strong>Diagnosis:</strong>
                                    <p><?php echo $record['diagnosis']; ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($record['treatment_plan']): ?>
                                <div class="mt-2">
                                    <strong>Treatment Plan:</strong>
                                    <p><?php echo $record['treatment_plan']; ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-2 text-muted">
                                <small>
                                    <i class="fas fa-clock"></i> 
                                    Recorded on <?php echo date('M d, Y h:i A', strtotime($record['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- Health Summary Card -->
<section class="container mb-5">
    <div class="card bg-light">
        <div class="card-body">
            <h4 class="card-title mb-3"><i class="fas fa-heartbeat text-danger me-2"></i>Health Summary</h4>
            <div class="row">
                <div class="col-md-3 col-6 mb-3">
                    <div class="text-center">
                        <i class="fas fa-tint fa-2x text-primary mb-2"></i>
                        <h6>Blood Group</h6>
                        <p class="h5"><?php echo $patient['blood_group'] ?? 'Not set'; ?></p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="text-center">
                        <i class="fas fa-allergies fa-2x text-warning mb-2"></i>
                        <h6>Allergies</h6>
                        <p><?php echo $patient['allergies'] ? 'Yes' : 'None'; ?></p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="text-center">
                        <i class="fas fa-pills fa-2x text-success mb-2"></i>
                        <h6>Chronic Conditions</h6>
                        <p><?php echo $patient['chronic_conditions'] ? 'Yes' : 'None'; ?></p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="text-center">
                        <i class="fas fa-calendar-check fa-2x text-info mb-2"></i>
                        <h6>Total Visits</h6>
                        <p class="h5"><?php echo count($history); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.date-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 10px;
    display: inline-block;
    min-width: 80px;
}
.date-badge .day {
    font-size: 24px;
    font-weight: bold;
    display: block;
}
.date-badge .month {
    font-size: 14px;
    text-transform: uppercase;
}
.year-divider {
    position: relative;
    text-align: center;
    margin: 30px 0 20px;
}
.year-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #dee2e6;
    z-index: 1;
}
.year-divider h3 {
    position: relative;
    display: inline-block;
    background: white;
    padding: 0 20px;
    z-index: 2;
    margin: 0;
}
</style>