<canvas id="myChart" width="400" height="400"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($labels);
    const totals = @json($totals);

    new Chart(document.getElementById('myChart'), {
        type: 'pie', // camembert
        data: {
            labels: labels,
            datasets: [{
                label: 'Nombre de réponses',
                data: totals,
                backgroundColor: labels.map(() => `hsl(${Math.random() * 360}, 70%, 60%)`),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
