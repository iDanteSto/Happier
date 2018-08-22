@extends('layouts.app')

@section('content')

                   
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-4">
            <div class="panel bg-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-user fa-5x"></i>
                        </div>
                        <div class="col-xs-7 text-right">
                            <div><br></div>
                            <div class="huge">{!! $adminscount !!} administradores</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="panel bg-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-angellist fa-5x"></i>
                        </div>
                        <div class="col-xs-7 text-right">
                            <div><br></div>
                            <div class="huge">{!! $guestcount !!} invitados</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="panel bg-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-7 text-right">
                            <div><br></div>
                            <div class="huge">{!! $userscount !!}  usuarios</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>                
    </div>
    <div class="row" id="graphs" style="margin-top: 60px">
          <div class="col-lg-6">
              <div class="panel panel-default">
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                        <div style="width: 250px;" >
                            <h4 align="center"><strong>Usuarios Registrados por Año</strong></h4>
                            <canvas id="myChart1" width="100" height="100"></canvas>
                        </div>
                        <div class="col-md-4">
                            <div class="dropdown">
                                <div class="form-group">
                                    <div class="col-md-12">
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
                  </div>
                  <!-- /.panel-body -->
              </div>
          </div>
          <div class="col-lg-6">
              <div class="panel panel-default">
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                        <div style="width: 250px;" >
                            <h4 align="center"><strong>Recomendaciones Globales</strong></h4>
                            <canvas id="pie-chart" width="100" height="100"></canvas>
                        </div>
                  </div>
                  <!-- /.panel-body -->
              </div>
          </div>
        <!--
          <div class="panel-group">
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-heading" align="center"><strong>Avatars</strong></div>
                    <div class="panel-body">
                      <div class="col-md-6">
                        <a href="{!!url('avatar_categories')!!}">
                              <div class="ibox">
                                  <div class="ibox-content product-box" style ="background-color: gold">
                                      <div class="product-imitation">
                                            <img alt="logo" src="{{url('/img/archive_img.png')}}" width="100" height="100">
                                      </div>
                                      <div class="product-desc">
                                          <p class="product-name" align="center"> Categorias</p>
                                      </div>
                                  </div>
                              </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                        <a href="{!!url('avatars')!!}">
                              <div class="ibox">
                                  <div class="ibox-content product-box" style ="background-color: gold">
                                      <div class="product-imitation">
                                            <img alt="logo" src="{{url('/img/archive_img.png')}}" width="100" height="100">
                                      </div>
                                      <div class="product-desc">
                                          <p class="product-name" align="center"> Avatars</p>
                                      </div>
                                  </div>
                              </div>
                            </a>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-heading" align="center"><strong>Recomendaciones</strong></div>
                    <div class="panel-body">
                      <div class="col-md-6">
                        <a href="{!!url('recommendation_categories')!!}">
                              <div class="ibox">
                                  <div class="ibox-content product-box" style ="background-color: gold">
                                      <div class="product-imitation">
                                            <img alt="logo" src="{{url('/img/archive_img.png')}}" width="100" height="100">
                                      </div>
                                      <div class="product-desc">
                                          <p class="product-name" align="center"> Temas</p>
                                      </div>
                                  </div>
                              </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                        <a href="{!!url('recommendations')!!}">
                              <div class="ibox">
                                  <div class="ibox-content product-box" style ="background-color: gold">
                                      <div class="product-imitation">
                                            <img alt="logo" src="{{url('/img/archive_img.png')}}" width="100" height="100">
                                      </div>
                                      <div class="product-desc">
                                          <p class="product-name" align="center"> Recom</p>
                                      </div>
                                  </div>
                              </div>
                            </a>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="panel panel-default">
                    <div class="panel-heading" align="center"><strong>Noticias</strong></div>
                    <div class="panel-body">
                      <div class="col-md-12">
                        <a href="{!!url('news')!!}">
                              <div class="ibox">
                                  <div class="ibox-content product-box" style ="background-color: gold">
                                      <div class="product-imitation">
                                            <img alt="logo" src="{{url('/img/archive_img.png')}}" width="100" height="100">
                                      </div>
                                      <div class="product-desc">
                                          <p class="product-name" align="center"> Noticia</p>
                                      </div>
                                  </div>
                              </div>
                            </a>
                        </div>
                    </div>
                  </div>
                </div>
          </div>
        -->
    </div>
    <div class="row" id="tableusers" style="margin-top: 60px">
      <div class="col-lg-8-offset-2">
              <div class="panel panel-default">
                  <div class="panel-heading">
                      <i class="fa fa-bar-chart-o fa-fw"></i> Usuarios
                      <div class="pull-right">
                          <div class="btn-group">
                          </div>
                      </div>
                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                      <table id="table_id" class="display">
                      <thead>
                            <tr>
                                <th>nickname</th>
                                <th>email</th>
                                <th>status</th>
                                <th>Pendientes</th>
                                <th>Completas</th>
                                <th>Rechazadas</th>
                                <th>Ignoradas</th>
                                <th>Guardadas</th>
                                <th>Mood Semanal</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($availableUsers as $userAtribute)
                                <tr>
                                    <input type="hidden" name="Id" value="{{$userAtribute->user_Id}}">
                                    <td>{{$userAtribute->nickname}}</td>
                                    <td>{{$userAtribute->email}}</td>
                                    <td align="center">{{$userAtribute->status}}</td>
                                    <?php
                                    $RecommendationInfo = DB::select('SELECT userRecommendation_Id,
                                    sum(fk_status_Id = ?) as pendiente,
                                    sum(fk_status_Id = ?) as completa,
                                    sum(fk_status_Id = ?) as rechazada,
                                    sum(fk_status_Id = ?) as ignorada,
                                    sum(fk_status_Id = ?) as guardada
                                  FROM userrecommendation WHERE fk_user_Id = ?' , [2,1,3,4,5,$userAtribute->user_Id]);

                                    $Avgmood = DB::select('SELECT 
                                    distinct(avg(mood)) as Average 
                                    FROM usermood 
                                    WHERE created_at >    DATE_SUB(NOW(), INTERVAL 1 WEEK) and fk_user_Id = ?
                                    GROUP BY WEEK(created_at)
                                    ORDER BY created_at desc limit 1' , [$userAtribute->user_Id]);
                                     ?>
                                    <td align="center">{{$RecommendationInfo[0]->pendiente}}</td>
                                    <td align="center">{{$RecommendationInfo[0]->completa}}</td>
                                    <td align="center">{{$RecommendationInfo[0]->rechazada}}</td>
                                    <td align="center">{{$RecommendationInfo[0]->ignorada}}</td>
                                    <td align="center">{{$RecommendationInfo[0]->guardada}}</td>
                                    @if (count($Avgmood) == 1)
                                    <td align="center"><?php echo bcdiv($Avgmood[0]->Average, 1, 1);?></td>    
                                    @elseif (count($Avgmood) < 1)
                                    <td align="center">0</td>    
                                    @endif
                                </tr>
                        @endforeach
                        </tbody>
                    </table> 
                  </div>
                  <!-- /.panel-body -->
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


//pie chart test
var pendings =  {{ $RecomStats[0]->pendiente}};
var completeds =  {{ $RecomStats[0]->completa}};
var rejecteds =  {{ $RecomStats[0]->rechazada}};
var ignoreds =  {{ $RecomStats[0]->ignorada}};
var saved =  {{ $RecomStats[0]->guardada}};

new Chart(document.getElementById("pie-chart"), {
    type: 'pie',
    data: {
      labels: ["Pendientes","Completas", "Rechazadas", "Ignoradas" , "Guardadas"],
      datasets: [{
        label: "Population (millions)",
        backgroundColor: ["#e7f215", "#0fed0b","#f4424b","#8e6704","#5bc0de"],
        data: [pendings,completeds,rejecteds,ignoreds , saved]
      }]
    },
    options: {
      title: {
        display: true,
        text: ''
      }
    }
});

</script>

                    
@endsection
