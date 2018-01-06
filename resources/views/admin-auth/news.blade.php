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

<form class="form-edit" method="GET" action="{{ url('news_edit') }}">
<div class="container">
    <div class="">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"></div>

                <div class="panel-body" align="left">
                   
                        
<table>                        
                       
                           

                         <br>
    <tr>
        <th>ID</th>
        <th>Titulo</th>
        <th>Contenido</th>
        <th>Imagen</th>
        <th>Status</th>
        <th>Fecha de creacion</th>
        <th>Comandos</th>
        <th>Comandos</th>
    </tr>  
                        

                        
                         
@foreach($availableNews as $item)

        <tr>
            <td>{{$item->news_Id}}</td> <input type="hidden" name="Id" value="{{$item->news_Id}}">
            <td>{{$item->title}}</td>
            <td>{{$item->content}}</td>
            <td><img src="{{$item->image}}" alt="img" width="100" height="100"></td>
            <td>{{$item->status}}</td>
            <td>{{$item->date_created}}</td>
            <td><a class="button Edit" name="{{$item->news_Id}}" >Edit</a></td>
            <td><a class="button Delete" name="{{$item->news_Id}}" >Delete</a></td>
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
                <div class="panel-heading">Crear Noticia</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('news_create') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="title" class="col-md-4 control-label">Titulo</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" required autofocus>

                               
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content" class="col-md-4 control-label">Contenido</label>

                            <div class="col-md-6">
                                <input id="content" type="text" class="form-control" name="content" required autofocus>

                               
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Imagen</label>

                            <div class="col-md-6">
                               <input type="file" name="file" id="file" multiple>
                              
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Crear Noticia
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
    window.location.href = "/news_edit/"+$(this).attr('name');
 });


$(document).on("click", ".Delete", function() {
//   console.log("inside";   <-- here it is
    console.log($(this).attr('name'));
    window.location.href = "/news_delete/"+$(this).attr('name');
 });

</script>
@endsection
