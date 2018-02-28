@extends('layouts.app')

@section('content')

<style>
.fixposition {
   

    position: fixed;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    width: 60%;
}
.fixposition2 {
   

    position: fixed;
    top: 90%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    width: 60%;
}

table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;

}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


<div class="fixposition">
    <div class="">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Usuarios Registrados</div>

<form class="form-horizontal" method="POST" action="{{ url('avatar_categ_register') }}">
                <div class="panel-body">
                   
                
                     
 <?php if($year != 0)
 {
?>
<div id="temps_div"></div>
<?= \Lava::render('LineChart', 'demochart' , 'temps_div') ?>
<select name="years">
<option value="0" disabled selected>Selecciona el año</option>    
  <?php   for($i = 0; $i <=$diffinYears; $i++){  ?>  
  <option value="{{$earliestyearOnlyYear+$i}}">{{$earliestyearOnlyYear+$i}}</option>
<?php 
                                                 } ?>
</select> 
<?php                                                
 }


  ?>                       

                        


                 

</div> 
</form>                   
                </div>
            </div>
        </div>
    </div>


<div class="fixposition2">
    <div class="">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Recommendaciones</div>

<form class="form-horizontal" method="POST" action="{{ url('avatar_categ_register') }}">
                <div class="panel-body">
                   
                        
                        
<div id="chart-div"></div>
// With Lava class alias
<?= Lava::render('DonutChart', 'IMDB', 'chart-div') ?>              

                        


                 

</div> 
</form>                   
                </div>
            </div>
        </div>
    </div>

<script>


$('select').change(function(){
    console.log($(this).attr('value'));
    window.location.href = "/metrics_report/"+$(this).attr('value'); 
});



</script>
@endsection
