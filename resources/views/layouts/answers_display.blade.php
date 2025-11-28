@extends('layouts.app')

@section('content')

    <h5 class="font-semibold">{{$question_title}}</h5>

    <canvas id="myChart" width="500" height="500" class="m-[200px]"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json(array_map('strval', $labels));
    console.log(labels)
    const totals = @json($totals);
    console.log(totals)

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
            responsive: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
</script>
@endsection
