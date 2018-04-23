@extends('layouts.app')

@section('content')

@if (Auth::guard('admin_user')->user()->level == '1')
<form class="form-edit" method="GET" action="{{ url('news_edit') }}">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Recomendaciones</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table">
                        <tbody>
                            @foreach($availableNews as $item)
                        <tr>
                            <td><img src="{{$item->image}}" alt="img" width="100" height="100" alt="img" class="img-rounded"></td>
                            <td>{{$item->date_created}}</td>
                            <td>{{$item->title}}</td>
                            <td><button class="btn btn-info " type="button" data-myimage="{{$item->image}}" data-mytitle="{{$item->title}}" data-mycontent="{{$item->content}}" data-myid="{{$item->news_Id}}" data-toggle="modal" data-target="#viewNew"><i class="fa fa-paste" ></i> Ver noticia</button>
                            </td>
                            <td>
                                <a class="btn btn-white btn-bitbucket" data-mytitle="{{$item->title}}" data-mycontent="{{$item->content}}" data-myid="{{$item->news_Id}}" data-toggle="modal" data-target="#editnews"><i class="fa fa-wrench"></i></a>
                            </td>
                            <td><button type="button" class="btn btn-w-s btn-danger"  name="{{$item->news_Id}}">Eliminar</button></td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<!--modal-->
<div id="editnews" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Editar la Noticia</h4>
    </div>
    <div class="modal-body">
        <form role="form" class="form-horizontal" method="POST" action="{{ url('news_update') }}">
            <div class="form-group">
                <div class="col-md-8">
                    <label>Titulo</label> 
                    <input id="title" type="text" class="form-control" name="title" value="" required>  
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-8">    
                    <label for="exampleTextarea">Contenido</label>
                    <textarea class="form-control" id="content" rows="7" name="content" value="" required ></textarea>
                </div>
            </div>
          <input id="NewsId" type="hidden" class="form-control" name="NewsId"  value="">
          <div class="form-group">
            <div class="col-md-8">
                <button class="btn btn-w-m btn-warning" type="submit"><strong>Actualizar</strong></button>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<div id="viewNew" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ver noticia</h4>
    </div>
    <div class="modal-body">
        <form role="form" class="form-horizontal" method="POST" action="{{ url('news_update') }}">
            <div class="form-group">
                <div class="col-md-8">
                    <label>Titulo</label> 
                    <input id="title" type="text" class="form-control" name="title" value="" disabled>  
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-8">    
                    <label for="exampleTextarea">Contenido</label>
                    <textarea class="form-control" id="content" rows="15" name="content" value="" disabled></textarea>
                </div>
            </div>
          <input id="NewsId" type="hidden" class="form-control" name="NewsId"  value="">
          
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

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
                            <button type="submit" class="btn btn-w-m btn-warning" style="width: 100%">
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
@else
<!--********************************admin level 0************************************************************-->
<form class="form-edit" method="GET" action="{{ url('news_edit') }}">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Recomendaciones</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table">
                        <tbody>
                            @foreach($availableNews as $item)
                        <tr>
                            <td><img src="{{$item->image}}" alt="img" width="100" height="100" alt="img" class="img-rounded"></td>
                            <td>{{$item->date_created}}</td>
                            <td>{{$item->title}}</td>
                            <td><button class="btn btn-info " type="button" data-myimage="{{$item->image}}" data-mytitle="{{$item->title}}" data-mycontent="{{$item->content}}" data-myid="{{$item->news_Id}}" data-toggle="modal" data-target="#viewNew"><i class="fa fa-paste" ></i> Ver noticia</button>
                            </td>
                            <td>
                                <a class="btn btn-white btn-bitbucket" data-mytitle="{{$item->title}}" data-mycontent="{{$item->content}}" data-myid="{{$item->news_Id}}" data-toggle="modal" data-target="#editnews"><i class="fa fa-wrench"></i></a>
                            </td>
                            <td><button type="button" class="btn btn-primary disabled"  name="{{$item->news_Id}}">Eliminar</button></td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<!--modal-->
<div id="editnews" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Editar la Noticia</h4>
    </div>
    <div class="modal-body">
        <form role="form" class="form-horizontal" method="POST" action="{{ url('news_update') }}">
            <div class="form-group">
                <div class="col-md-8">
                    <label>Titulo</label> 
                    <input id="title" type="text" class="form-control" name="title" value="" disabled>  
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-8">    
                    <label for="exampleTextarea">Contenido</label>
                    <textarea class="form-control" id="content" rows="7" name="content" value="" disabled ></textarea>
                </div>
            </div>
          <input id="NewsId" type="hidden" class="form-control" name="NewsId"  value="">
          <div class="form-group">
            <div class="col-md-8">
                
            </div>
        </div>
    </form>
    <button class="btn btn-primary disabled" type="submit"><strong>Actualizar</strong></button>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<div id="viewNew" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ver noticia</h4>
    </div>
    <div class="modal-body">
        <form role="form" class="form-horizontal" method="POST" action="{{ url('news_update') }}">
            <div class="form-group">
                <div class="col-md-8">
                    <label>Titulo</label> 
                    <input id="title" type="text" class="form-control" name="title" value="" disabled>  
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-8">    
                    <label for="exampleTextarea">Contenido</label>
                    <textarea class="form-control" id="content" rows="15" name="content" value="" disabled></textarea>
                </div>
            </div>
          <input id="NewsId" type="hidden" class="form-control" name="NewsId"  value="">
          
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

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
                                <input id="title" type="text" class="form-control" name="title" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content" class="col-md-4 control-label">Contenido</label>

                            <div class="col-md-6">
                                <input id="content" type="text" class="form-control" name="content" disabled>


                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Imagen</label>

                            <div class="col-md-6">
                             <input type="file" name="file" id="file" multiple disabled>

                         </div>
                     </div>
                     </form>
                     <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary disabled" style="width: 100%">
                            Crear Noticia
                        </button>
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
</div>
</div>
@endif













<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
//js to show modal with data
$('#editnews').on('show.bs.modal',function(event){
 var button = $(event.relatedTarget)
 var NewsId = button.data('myid')
 var Title = button.data('mytitle')
 var Content = button.data('mycontent')
 var modal = $(this)
 modal.find('.modal-body #title').val(Title)
 modal.find('.modal-body #content').val(Content)
 modal.find('.modal-body #NewsId').val(NewsId)
});
$('#viewNew').on('show.bs.modal',function(event){
 var button = $(event.relatedTarget)
 var NewsId = button.data('myid')
 var Title = button.data('mytitle')
 var Content = button.data('mycontent')
 var Image = button.data('myimage')
 var modal = $(this)
 modal.find('.modal-body #title').val(Title)
 modal.find('.modal-body #content').val(Content)
 modal.find('.modal-body #NewsId').val(NewsId)
 modal.find('.modal-body #NewsImage').val(Image)
});
//JS to delete categorie
$(document).on("click", ".btn-danger", function() {
        //   console.log("inside";   <-- here it is
        console.log($(this).attr('name'));
        window.location.href = "/news_delete/"+$(this).attr('name');
    });
</script>
@endsection
