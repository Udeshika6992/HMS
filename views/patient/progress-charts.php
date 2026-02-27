<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Health Progress Tracking</h1>
        <p class="lead">Monitor your health metrics over time</p>
    </div>
</section>

<!-- Chart Controls -->
<section class="container mb-4">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="metricSelect" class="form-label">Select Metric</label>
                    <select class="form-select" id="metricSelect">
                        <option value="weight">Weight (kg)</option>
                        <option value="blood_pressure">Blood Pressure</option>
                        <option value="heart_rate">Heart Rate</option>
                        <option value="blood_sugar">Blood Sugar</option>
                        <option value="bmi">BMI</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="periodSelect" class="form-label">Time Period</label>
                    <select class="form-select" id="periodSelect">
                        <option value="7">Last 7 days</option>
                        <option value="30" selected>Last 30 days</option>
                        <option value="90">Last 3 months</option>
                        <option value="180">Last 6 months</option>
                        <option value="365">Last year</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="updateChart">
                        <i class="fas fa-sync-alt"></i> Update Chart
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Chart -->
<section class="container mb-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Progress Chart</h5>
        </div>
        <div class="card-body">
            <canvas id="progressChart" style="width:100%; max-width:100%; height:400px;"></canvas>
        </div>
    </div>
</section>

<!-- Metrics Cards -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-weight text-primary"></i>
                <h3 id="avgWeight">-</h3>
                <p>Avg Weight (kg)</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-heartbeat text-danger"></i>
                <h3 id="avgBP">-</h3>
                <p>Avg BP (mmHg)</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-heart text-success"></i>
                <h3 id="avgHR">-</h3>
                <p>Avg Heart Rate</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-chart-bar text-info"></i>
                <h3 id="avgBMI">-</h3>
                <p>Avg BMI</p>
            </div>
        </div>
    </div>
</section>

<!-- Vitals History Table -->
<section class="container mb-5">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Vitals History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Blood Pressure</th>
                            <th>Heart Rate</th>
                            <th>Weight</th>
                            <th>BMI</th>
                            <th>Blood Sugar</th>
                            <th>Oxygen %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vitals as $vital): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($vital['record_date'])); ?></td>
                            <td>
                                <?php if ($vital['blood_pressure_systolic']): ?>
                                    <?php echo $vital['blood_pressure_systolic']; ?>/<?php echo $vital['blood_pressure_diastolic']; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo $vital['heart_rate'] ?: '-'; ?></td>
                            <td><?php echo $vital['weight'] ? $vital['weight'] . ' kg' : '-'; ?></td>
                            <td><?php echo $vital['bmi'] ? number_format($vital['bmi'], 1) : '-'; ?></td>
                            <td><?php echo $vital['blood_sugar_fasting'] ?: '-'; ?></td>
                            <td><?php echo $vital['oxygen_saturation'] ? $vital['oxygen_saturation'] . '%' : '-'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let myChart = null;

$(document).ready(function() {
    loadChartData('weight', 30);
    
    $('#updateChart').click(function() {
        var metric = $('#metricSelect').val();
        var period = $('#periodSelect').val();
        loadChartData(metric, period);
    });
});

function loadChartData(metric, days) {
    $.ajax({
        url: '<?php echo BASE_URL; ?>patient/get-chart-data',
        method: 'GET',
        data: { metric: metric, period: days },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                updateChart(response.data, metric);
                updateStats(response.data, metric);
            }
        }
    });
}

function updateChart(data, metric) {
    var ctx = document.getElementById('progressChart').getContext('2d');
    
    // Destroy existing chart
    if (myChart) {
        myChart.destroy();
    }
    
    var chartData = {
        labels: [],
        datasets: []
    };
    
    if (metric === 'blood_pressure') {
        chartData.labels = data.map(d => d.record_date);
        chartData.datasets = [
            {
                label: 'Systolic',
                data: data.map(d => d.blood_pressure_systolic),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1
            },
            {
                label: 'Diastolic',
                data: data.map(d => d.blood_pressure_diastolic),
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.1
            }
        ];
    } else {
        chartData.labels = data.map(d => d.record_date || d.tracking_date);
        
        if (metric === 'weight') {
            chartData.datasets = [{
                label: 'Weight (kg)',
                data: data.map(d => d.weight),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }];
        } else if (metric === 'heart_rate') {
            chartData.datasets = [{
                label: 'Heart Rate (bpm)',
                data: data.map(d => d.heart_rate),
                borderColor: 'rgb(255, 159, 64)',
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                tension: 0.1
            }];
        } else if (metric === 'blood_sugar') {
            chartData.datasets = [{
                label: 'Blood Sugar (mg/dL)',
                data: data.map(d => d.blood_sugar_fasting || d.metric_value),
                borderColor: 'rgb(153, 102, 255)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                tension: 0.1
            }];
        } else if (metric === 'bmi') {
            chartData.datasets = [{
                label: 'BMI',
                data: data.map(d => d.bmi),
                borderColor: 'rgb(255, 205, 86)',
                backgroundColor: 'rgba(255, 205, 86, 0.2)',
                tension: 0.1
            }];
        }
    }
    
    myChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: true,
                    text: $('#metricSelect option:selected').text() + ' - Last ' + $('#periodSelect').val() + ' days'
                }
            }
        }
    });
}

function updateStats(data, metric) {
    if (data.length === 0) return;
    
    // Calculate averages
    let weights = data.filter(d => d.weight).map(d => parseFloat(d.weight));
    let bps = data.filter(d => d.blood_pressure_systolic).map(d => parseInt(d.blood_pressure_systolic));
    let bpd = data.filter(d => d.blood_pressure_diastolic).map(d => parseInt(d.blood_pressure_diastolic));
    let hrs = data.filter(d => d.heart_rate).map(d => parseInt(d.heart_rate));
    let bmis = data.filter(d => d.bmi).map(d => parseFloat(d.bmi));
    
    if (weights.length > 0) {
        $('#avgWeight').text((weights.reduce((a, b) => a + b, 0) / weights.length).toFixed(1));
    }
    
    if (bps.length > 0 && bpd.length > 0) {
        let avgSystolic = Math.round(bps.reduce((a, b) => a + b, 0) / bps.length);
        let avgDiastolic = Math.round(bpd.reduce((a, b) => a + b, 0) / bpd.length);
        $('#avgBP').text(avgSystolic + '/' + avgDiastolic);
    }
    
    if (hrs.length > 0) {
        $('#avgHR').text(Math.round(hrs.reduce((a, b) => a + b, 0) / hrs.length));
    }
    
    if (bmis.length > 0) {
        $('#avgBMI').text((bmis.reduce((a, b) => a + b, 0) / bmis.length).toFixed(1));
    }
}
</script>