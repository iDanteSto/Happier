@extends('layouts.app')

@section('content')
<style>
.positioner {
   

    position: fixed;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    width: 60%;
}
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

<form class="form-edit" method="GET" action="{{ url('avatar_categ_edit') }}">
<div class="">
    <div class="">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Show</div>

                <div class="panel-body" align="left">
                   
                        
<table>                        
                      
                            

                         <br>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripcion</th>
        <th>Categoria</th>
        <th>Imagen</th>
        <th>Tiempo del dia</th>
        <th>Comandos</th>
        <th>Comandos</th>
    </tr>  
                   
                     
                          
@foreach($availableRecommendations as $Recommendation)
        <tr>
            <td>{{$Recommendation->recommendation_Id}}</td> <input type="hidden" name="Id" value="{{$Recommendation->recommendation_Id}}">
            <td>{{$Recommendation->name}}</td>
            <td>{{$Recommendation->description}}</td>
<?php $Categname = DB::table('category')->select('description')->where('category_Id', $Recommendation->fk_category_Id)->get(); ?>           
            <td>{{$Categname[0]->description}}</td>
            <td><img src="{{$Recommendation->image}}" alt="img" width="100" height="100"></td>
<?php $Timeofdayname = DB::table('timesofday')->select('description')->where('TimesofDay_Id', $Recommendation->timeofday)->get(); ?>         
            <td>{{$Timeofdayname[0]->description}}</td>
            <td><a class="button Edit" name="{{$Recommendation->recommendation_Id}}" >Editar</a></td>
            <td><a class="button Delete" name="{{$Recommendation->recommendation_Id}}" >Borrar</a></td>
        </tr>
@endforeach


                            
                    
</table>
                        
                </div>
            </div>
        </div>
    </div>
</div>
</form>



<div class="">
    <div class="">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Crear Recomendacion</b></div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('recommendation_create') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Nombre</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="" required autofocus>

                               
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Description" class="col-md-4 control-label">Descripcion</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" required autofocus>

                               
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Imagen</label>

                            <div class="col-md-6">
                               <input type="file" name="file" id="file" multiple>
                              
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Category" class="col-md-4 control-label">Categoria</label>

                            <div class="col-md-6">
                             
                                <select id="fkCategId" name="fkCategId">
   @foreach($availableCategories as $categs)                                   
  <option value="{{$categs->category_Id}}" >{{$categs->description}}</option>
  @endforeach        
                                </select>
                        
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Frequency" class="col-md-4 control-label">Tiempo del dia</label>

                            <div class="col-md-6">
                             
                                <select id="TimesofDay_Id" name="TimesofDay_Id">
   @foreach($availableTimesofDay as $times)                                   
  <option value="{{$times->TimesofDay_Id}}" >{{$times->description}}</option>
  @endforeach        
                                </select>
                        
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Subir Recomendacion
                                </button>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on("click", ".Edit", function() {
//   console.log("inside";   <-- here it is
    console.log($(this).attr('name'));
    window.location.href = "/recommendation_edit/"+$(this).attr('name');
 });


$(document).on("click", ".Delete", function() {
//   console.log("inside";   <-- here it is
    console.log($(this).attr('name'));
    window.location.href = "/recommendation_delete/"+$(this).attr('name');
 });
</script>
@endsection
