@extends('layouts.app')

@section('content')

<style>
.fixposition2 {
   

    position: fixed;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    width: 60%;
}

</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<div class="container">
    <div class="fixposition2">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" >Edit the category</div>

<form class="form-horizontal" method="POST" action="{{ url('news_update') }}">
                <div class="panel-body">
                   

 
                       
                            
                     
                        <input type="hidden" name="id" value="{{$InfoNews[0]->news_Id}}">
                        <div class="col-md-5">

                            <label for="description" class="col-md-4 control-label">Titulo</label>

                            <div class="">
                                <input id="title" type="text" class="form-horizontal" name="title"  value="{{$InfoNews[0]->title}}" required >
                            </div>
                        </div>
                         <div class="col-md-5">

                            <label for="description" class="col-md-4 control-label">Contenido</label>

                            <div class="">
                                <input id="content" type="text" class="form-horizontal" name="content"  value="{{$InfoNews[0]->content}}" required >
                            </div>
                        </div>
                        <div class="col-md-1">        
                                <button type="submit" class="btn btn-primary" >               
                                    Actualizar
                                </button>
                                
                         
                        </div>
</div> 
</form>                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
