<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Patient Progress Tracking</h1>
        <p class="lead">Track and monitor patient health metrics</p>
    </div>
</section>

<!-- Patient Info -->
<section class="container mb-4">
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <img src="<?php echo UPLOAD_URL; ?>profiles/<?php echo $patient['profile_image'] ?? 'default-avatar.png'; ?>" 
                         class="rounded-circle" 
                         style="width: 80px; height: 80px; object-fit: cover;">
                </div>
                <div class="col-md-6">
                    <h4><?php echo htmlspecialchars($patient['full_name']); ?></h4>
                    <p class="text-muted mb-1">
                        <i class="fas fa-calendar-alt me-2"></i>DOB: <?php echo date('M d, Y', strtotime($patient['date_of_birth'] ?? '2000-01-01')); ?> |
                        <i class="fas fa-tint ms-2 me-1"></i>Blood Group: <?php echo $patient['blood_group'] ?? 'N/A'; ?>
                    </p>
                    <p>
                        <span class="badge bg-info">Last Visit: <?php echo $last_visit ?? 'N/A'; ?></span>
                        <span class="badge bg-success ms-2">Total Visits: <?php echo $total_visits ?? 0; ?></span>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary" onclick="addProgressData()">
                        <i class="fas fa-plus"></i> Add Progress Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Progress Charts -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-weight me-2"></i>Weight Tracking</h5>
                </div>
                <div class="card-body">
                    <canvas id="weightChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-heartbeat me-2"></i>Blood Pressure</h5>
                </div>
                <div class="card-body">
                    <canvas id="bpChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-heart me-2"></i>Heart Rate</h5>
                </div>
                <div class="card-body">
                    <canvas id="heartRateChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Blood Sugar</h5>
                </div>
                <div class="card-body">
                    <canvas id="bloodSugarChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Progress Data Table -->
<section class="container mb-5">
    <div class="card">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Progress History</h5>
            <div>
                <select class="form-select form-select-sm" id="metricFilter" style="width: auto; display: inline-block;">
                    <option value="all">All Metrics</option>
                    <option value="weight">Weight</option>
                    <option value="blood_pressure">Blood Pressure</option>
                    <option value="heart_rate">Heart Rate</option>
                    <option value="blood_sugar">Blood Sugar</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Metric</th>
                            <th>Value</th>
                            <th>Unit</th>
                            <th>Doctor</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody id="progressTableBody">
                        <?php if (empty($progress_data)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">No progress data available</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($progress_data as $data): ?>
                                <tr>
                                    <td><?php echo date('M d, Y', strtotime($data['tracking_date'])); ?></td>
                                    <td><?php echo ucwords(str_replace('_', ' ', $data['metric_name'])); ?></td>
                                    <td><strong><?php echo $data['metric_value']; ?></strong></td>
                                    <td><?php echo $data['metric_unit'] ?? '-'; ?></td>
                                    <td>Dr. <?php echo $data['doctor_name']; ?></td>
                                    <td><?php echo $data['notes'] ?? '-'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Add Progress Modal -->
<div class="modal fade" id="addProgressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Progress Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>doctor/add-progress/<?php echo $patient['id']; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="metric_name" class="form-label">Metric Name</label>
                        <select class="form-select" id="metric_name" name="metric_name" required>
                            <option value="">Select Metric</option>
                            <option value="weight">Weight</option>
                            <option value="blood_pressure_systolic">Blood Pressure (Systolic)</option>
                            <option value="blood_pressure_diastolic">Blood Pressure (Diastolic)</option>
                            <option value="heart_rate">Heart Rate</option>
                            <option value="blood_sugar">Blood Sugar</option>
                            <option value="bmi">BMI</option>
                            <option value="custom">Custom Metric</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="customMetricField" style="display: none;">
                        <label for="custom_metric" class="form-label">Custom Metric Name</label>
                        <input type="text" class="form-control" id="custom_metric" name="custom_metric" placeholder="e.g., Pain Level">
                    </div>
                    
                    <div class="mb-3">
                        <label for="metric_value" class="form-label">Value</label>
                        <input type="text" class="form-control" id="metric_value" name="metric_value" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="metric_unit" class="form-label">Unit</label>
                        <input type="text" class="form-control" id="metric_unit" name="metric_unit" placeholder="e.g., kg, bpm, mmHg">
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Progress Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    
    document.getElementById('metricFilter').addEventListener('change', filterTable);
    document.getElementById('metric_name').addEventListener('change', function() {
        const customField = document.getElementById('customMetricField');
        customField.style.display = this.value === 'custom' ? 'block' : 'none';
    });
});

function initializeCharts() {
    // Sample data - in real app, this would come from PHP
    const dates = <?php echo json_encode(array_column($progress_data ?? [], 'tracking_date')); ?>;
    
    // Weight Chart
    const weightCtx = document.getElementById('weightChart').getContext('2d');
    new Chart(weightCtx, {
        type: 'line',
        data: {
            labels: dates.slice(-10),
            datasets: [{
                label: 'Weight (kg)',
                data: [72, 71.5, 71, 70.5, 70, 69.5, 69, 68.5, 68, 67.5],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Blood Pressure Chart
    const bpCtx = document.getElementById('bpChart').getContext('2d');
    new Chart(bpCtx, {
        type: 'line',
        data: {
            labels: dates.slice(-10),
            datasets: [
                {
                    label: 'Systolic',
                    data: [120, 118, 115, 112, 110, 108, 105, 103, 100, 98],
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                },
                {
                    label: 'Diastolic',
                    data: [80, 79, 78, 76, 75, 74, 72, 71, 70, 68],
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Heart Rate Chart
    const hrCtx = document.getElementById('heartRateChart').getContext('2d');
    new Chart(hrCtx, {
        type: 'line',
        data: {
            labels: dates.slice(-10),
            datasets: [{
                label: 'Heart Rate (bpm)',
                data: [72, 71, 73, 70, 69, 68, 67, 66, 65, 64],
                borderColor: 'rgb(255, 159, 64)',
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Blood Sugar Chart
    const bsCtx = document.getElementById('bloodSugarChart').getContext('2d');
    new Chart(bsCtx, {
        type: 'line',
        data: {
            labels: dates.slice(-10),
            datasets: [{
                label: 'Blood Sugar (mg/dL)',
                data: [95, 98, 92, 96, 94, 91, 93, 90, 88, 85],
                borderColor: 'rgb(153, 102, 255)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function addProgressData() {
    $('#addProgressModal').modal('show');
}

function filterTable() {
    const metric = document.getElementById('metricFilter').value;
    const rows = document.querySelectorAll('#progressTableBody tr');
    
    rows.forEach(row => {
        if (metric === 'all') {
            row.style.display = '';
        } else {
            const metricCell = row.cells[1]?.textContent.toLowerCase() || '';
            row.style.display = metricCell.includes(metric.replace('_', ' ')) ? '' : 'none';
        }
    });
}
</script>