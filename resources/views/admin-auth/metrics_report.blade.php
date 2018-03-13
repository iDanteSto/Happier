@extends('layouts.app')
@section('content')

<div style="width: 400px;" >
    <h4 align="center"><strong>Usuarios Registrados por Año</strong></h4>
    <canvas id="myChart1" width="100" height="100"></canvas>
</div>
<div class="col-md-4">
    <div class="dropdown">
        <div class="form-group">
            <div class="col-md-8">
                <label for="year">Año</label>
                <select class="form-control m-b" id="fkCategId" name="fkCategId">     
                 @for($i = 0; $i <=$diffinYears; $i++)
                <option value="{{$earliestCarbon+$i}}">{{$earliestCarbon+$i}}</option>
                 @endfor
                </select> 
            </div>
        </div>
    </div>
</div>


<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<!--<script src="js/main.js"></script> -->
<script>
    var datos = <?php echo json_encode($data); ?>;

    var ctx1 = document.getElementById("myChart1");
    var myChart1 = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: datos.meses,
            datasets: [{
                label: '# Usuarios',
                data: datos.usuarios,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        userCallback: function(label, index, labels) {
                            if (Math.floor(label) === label) {
                                return label;
                            }

                        },
                    }
                }]
            }
        }
    });

    $('#fkCategId').on('change', function(){
        var valor = $(this).val();

        $.ajax({
            url: './get_metrics_graph',
            type: 'POST',
            data: { 
                year: valor,
            },
            cache: false,
            dataType: 'json',
            success: function(data) {
                myChart1.data.labels = data.meses;
                myChart1.data.datasets[0].data = data.usuarios;   
                myChart1.update();
            }, error: function(data) {
                console.log(data);
                console.log('error :(');                    
            }
        });
    });
</script>

@endsection
