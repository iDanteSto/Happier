@extends('layouts.app')

@section('content_god')

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

<form class="form-edit" method="GET" action="{{ url('admin_edit') }}">
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
        <th>Nombre</th>
        <th>Email</th>
        <th>creado</th>
        <th>Actualizado</th>
        <th>Comandos</th>
        <th>Comandos</th>
    </tr>  
                        

                        
                         
@foreach($availableAdmins as $item)

        <tr>
            <td>{{$item->id}}</td> <input type="hidden" name="Id" value="{{$item->id}}">
            <td>{{$item->name}}</td>
            <td>{{$item->email}}</td>
            <td>{{$item->created_at}}</td>
            <td>{{$item->updated_at}}</td>
            <td><a class="button Edit" name="{{$item->id}}" >Editar</a></td>
            <td><a class="button Delete" name="{{$item->id}}" >Borrar</a></td>
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
                <div class="panel-heading">Crear Nuevo Administrador</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('admin_create') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
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
    window.location.href = "/admin_edit/"+$(this).attr('name');
 });


$(document).on("click", ".Delete", function() {
//   console.log("inside";   <-- here it is
    console.log($(this).attr('name'));
    window.location.href = "/admin_delete/"+$(this).attr('name');
 });

</script>
@endsection
