<canvas id="progressChart"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('progressChart'), {
  type: 'line',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr'],
    datasets: [{
      label: 'Blood Pressure',
      data: [120, 125, 130, 128],
      borderColor: 'red',
      fill: false
    }]
  }
});
</script>
