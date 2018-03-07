@extends('layouts.app')

@section('content')



<form class="form-edit" method="GET" action="{{ url('avatar_categ_edit') }}">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Avatars</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table">
                        <tbody>
                            @foreach($existingAvatars as $Avatars)
                                <tr>
                                    
                                    <td><img src="{{$Avatars->link}}" alt="img" width="60" height="60" class="img-rounded"></td>
                                    <td>{{$Avatars->name}}</td>
                                    <td>{{$Avatars->description}}</td>
                                    @foreach($availableCategories as $categs)
                                    @if ($Avatars->fk_avatar_categories_Id == $categs->avatar_categories_Id)
                                    <td><strong>{{$categs->name}}</strong></td>
                                    @break
                                    @endif  
                                    @endforeach 
                                    <td>
                                        <a class="btn btn-white btn-bitbucket" data-myname="{{$Avatars->name}}" data-mydescription="{{$Avatars->description}}" data-myid="{{$Avatars->avatar_Id}}" data-toggle="modal" data-target="#editAvatar"><i class="fa fa-wrench"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-w-s btn-danger"  name="{{$Avatars->avatar_Id}}">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>


<div id="editAvatar" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Editar el Avatar</h4>
      </div>
      <div class="modal-body">
        <form role="form" class="form-horizontal" method="POST" action="{{ url('avatar_update') }}">
                <div class="form-group">
                    <div class="col-md-8">
                    <label>Nombre</label> 
                    <input id="name" type="text" class="form-control" name="name" value="" required>  
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                    <label>Descripcion</label>  
                    <input id="description" type="text" class="form-control" name="description"  value="" required >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                            <label for="Category">Categoria</label>
                                <select class="form-control m-b" id="fkCategId" name="fkCategId">
  @foreach($availableCategories as $categs)                                   
  <option value="{{$categs->avatar_categories_Id}}" >{{$categs->name}}</option>
  @endforeach        
                                </select>
                            </div>
                        </div>
                        <input id="AvatarId" type="hidden" class="form-control" name="AvatarId"  value="">
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


<div class="">
    <div class="">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Crear un Avatar</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('CreateAvatar') }}" enctype="multipart/form-data">
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
                            <label for="Category" class="col-md-4 control-label">Categoria</label>
                            <div class="col-md-6">
                                <select class="form-control m-b" id="fkCategId" name="fkCategId">
  @foreach($availableCategories as $categs)                                   
  <option value="{{$categs->avatar_categories_Id}}" >{{$categs->name}}</option>
  @endforeach        
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="imagen" class="col-md-4 control-label">Imagen</label>
                            <div class="col-md-6">
                               <input type="file" name="file" id="file" multiple>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-w-m btn-warning" style="width: 100%">
                                    Crear avatar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
$('#editAvatar').on('show.bs.modal',function(event){
     //var category_id = $(this).attr('name');
     var button = $(event.relatedTarget)
     var AvatarId = button.data('myid')
     var Name = button.data('myname')
     var Description = button.data('mydescription')
     var modal = $(this)
     modal.find('.modal-body #name').val(Name)
     modal.find('.modal-body #description').val(Description)
     modal.find('.modal-body #AvatarId').val(AvatarId)
     //$('#myModal').modal('show');
});
//JS to delete categorie
 $(document).on("click", ".btn-danger", function() {
        //   console.log("inside";   <-- here it is
        console.log($(this).attr('name'));
        window.location.href = "/avatar_categories_delete/"+$(this).attr('name');
    });

</script>
@endsection
