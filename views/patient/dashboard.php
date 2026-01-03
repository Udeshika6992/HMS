<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>Patient Dashboard</h1>
    <div>
        <a href="index.php?page=appointment">Channel Doctor</a>
        <a href="index.php?page=logout">Logout</a>
    </div>
</div>

<!-- SUMMARY -->
<div class="card">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'Patient') ?>
</h2>
</div>

<!-- DASHBOARD CARDS -->
<div class="card">
    <h2>Your Overview</h2>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="icon">🏥</div>
            <h3>Total Visits</h3>
            <div class="count"><?= $visitCount ?></div>
        </div>

        <div class="dashboard-card">
            <div class="icon">📊</div>
            <h3>Progress Status</h3>
            <div class="count" style="font-size:18px;">
                <?= htmlspecialchars($progressStatus) ?>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS -->
<div class="card">
    <h3>Health Overview</h3>

    <div style="display:flex; gap:30px; flex-wrap:wrap;">
        <!-- BAR CHART -->
        <div style="width:45%;">
            <canvas id="visitChart"></canvas>
        </div>

        <!-- PIE CHART -->
        <div style="width:45%;">
            <canvas id="progressChart"></canvas>
        </div>
    </div>
</div>

<!-- VISIT HISTORY -->
<div class="card">
    <h3>Visit History</h3>

    <?php if ($visits && $visits->num_rows > 0): ?>
        <table>
            <tr>
                <th>Visit Date</th>
                <th>Doctor Notes</th>
            </tr>

            <?php while ($row = $visits->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['visit_date']) ?></td>
                    <td><?= htmlspecialchars($row['notes']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No visit history available.</p>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital | Government Healthcare Service</p>
</div>

<!-- CHART SCRIPTS -->
<script>
// ================= BAR CHART =================
new Chart(document.getElementById('visitChart'), {
    type: 'bar',
    data: {
        labels: ['Visits'],
        datasets: [{
            label: 'Total Visits',
            data: [<?= $visitCount ?>],
            backgroundColor: '#1976d2'
        }]
    },
    options: {
        animation: { duration: 1500 },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// ================= PIE CHART =================
new Chart(document.getElementById('progressChart'), {
    type: 'pie',
    data: {
        labels: ['Progress'],
        datasets: [{
            data: [1],
            backgroundColor: ['#2e7d32']
        }]
    },
    options: {
        animation: { duration: 1500 },
        plugins: {
            legend: { display: false },
            title: {
                display: true,
                text: '<?= $progressStatus ?>'
            }
        }
    }
});
</script>

</body>
</html>
