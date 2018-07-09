@extends('layouts.app')

@section('content')

 @if (Auth::guard('admin_user')->user()->level == '1')
<form class="form-edit" method="GET" action="{{ url('avatar_categ_edit') }}">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Categorias de Avatars</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <!--Table-->
                <div class="ibox-content">
                    <table class="table">
                        <tbody>
                        <?php 
                        $editableArray = [];
                        $editlenght = count($checkIfEditable);
                        ?>
                        @foreach($availableCategories as $categs)
                        <?php 
                            $disponible = true; 
                            foreach ($checkIfEditable as $obj) { 
                                if($categs->avatar_categories_Id == $obj->fk_avatar_categories_Id){ 
                                    $disponible = false;
                                }
                            } 
                            if($disponible){ ?>
                                <tr>
                                    <input type="hidden" name="Id" value="{{$categs->avatar_categories_Id}}">
                                    <td>{{$categs->name}}</td>
                                    <td>{{$categs->description}}</td>

                                    <td>
                                        <a class="btn btn-white btn-bitbucket" data-myname="{{$categs->name}}" data-mydescription="{{$categs->description}}" data-myid="{{$categs->avatar_categories_Id}}" data-toggle="modal" data-target="#edit"><i class="fa fa-wrench"></i></a>
                                    </td>
                                    <td><button type="button" class="btn btn-w-s btn-danger"  name="{{$categs->avatar_categories_Id}}">Eliminar</button></td>
                                </tr>
                            <?php }else{ ?>
                                <tr>
                                    <input type="hidden" name="Id" value="{{$categs->avatar_categories_Id}}">
                                    <td>{{$categs->name}}</td>
                                    <td>{{$categs->description}}</td>
                                    <td>
                                        <a class="btn btn-white btn-bitbucket" data-myname="{{$categs->name}}" data-mydescription="{{$categs->description}}" data-myid="{{$categs->avatar_categories_Id}}" data-toggle="modal" data-target="#edit"><i class="fa fa-wrench"></i></a>
                                    </td>
                                    <td></td>
                                </tr>
                            <?php 
                                } 
                            ?>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!--Table-->
            </div>
        </div>
    </div>
</form>

 <div id="edit" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edita la Categoria</h4>
      </div>
      <div class="modal-body">
        <form role="form" class="form-horizontal" method="POST" action="{{ url('avatar_categ_update') }}">
                <div class="form-group">
                    <div class="col-md-8">
                    <label>Nombre</label> 
                    <input id="name" type="text" class="form-control" name="name" value="" required>  
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">    
                    <label for="exampleTextarea">Descripcion</label>
                    <textarea class="form-control" id="description" rows="3" name="description" value="" required ></textarea>
                    </div>
                    </div>
                       
                        <input id="CategId" type="hidden" class="form-control" name="CategId"  value="">
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
        <div class="col-md-6 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Crear Nueva Categoria de Avatar</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('avatar_categ_register') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Nombre</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-md-4 control-label">Descripcion</label>
                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-4">
                                <button type="submit" class="btn btn-w-m btn-warning" style="width: 100%">
                                    Registrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--********************************admin level 0************************************************************-->
@else 


<form class="form-edit" method="GET" action="{{ url('avatar_categ_edit') }}">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Categorias de Avatars</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table">
                        <tbody>
                        <?php 
                        $editableArray = [];
                        $editlenght = count($checkIfEditable);
                        ?>
                        @foreach($availableCategories as $categs)
                        <?php 
                            $disponible = true; 
                            foreach ($checkIfEditable as $obj) { 
                                if($categs->avatar_categories_Id == $obj->fk_avatar_categories_Id){ 
                                    $disponible = false;
                                }
                            } 
                            if($disponible){ ?>
                                <tr>
                                    <input type="hidden" name="Id" value="{{$categs->avatar_categories_Id}}">
                                    <td>{{$categs->name}}</td>
                                    <td>{{$categs->description}}</td>

                                    <td>
                                        <a class="btn btn-white btn-bitbucket" data-myname="{{$categs->name}}" data-mydescription="{{$categs->description}}" data-myid="{{$categs->avatar_categories_Id}}" data-toggle="modal" data-target="#edit"><i class="fa fa-wrench"></i></a>
                                    </td>
                                    <td><button type="button" class="btn btn-primary disabled"  name="{{$categs->avatar_categories_Id}}">Eliminar</button></td>
                                </tr>
                            <?php }else{ ?>
                                <tr>
                                    <input type="hidden" name="Id" value="{{$categs->avatar_categories_Id}}">
                                    <td>{{$categs->name}}</td>
                                    <td>{{$categs->description}}</td>
                                    <td>
                                        <a class="btn btn-white btn-bitbucket" data-myname="{{$categs->name}}" data-mydescription="{{$categs->description}}" data-myid="{{$categs->avatar_categories_Id}}" data-toggle="modal" data-target="#edit"><i class="fa fa-wrench"></i></a>
                                    </td>
                                    <td></td>
                                </tr>
                            <?php 
                                } 
                            ?>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

 <div id="edit" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edita la Categoria</h4>
      </div>
      <div class="modal-body">
        <form role="form" class="form-horizontal" method="POST" action="{{ url('avatar_categ_update') }}">
                <div class="form-group">
                    <div class="col-md-8">
                    <label>Nombre</label> 
                    <input id="name" type="text" class="form-control" name="name" value="" disabled>  
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">    
                    <label for="exampleTextarea">Descripcion</label>
                    <textarea class="form-control" id="description" rows="3" name="description" value="" disabled ></textarea>
                    </div>
                    </div>
                        <input id="CategId" type="hidden" class="form-control" name="CategId"  value="">
                <div class="form-group">
                    <div class="col-md-8">
                    </div>
                </div>
        </form>
        <button class="btn btn-primary disabled" type=""><strong>Actualizar</strong></button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="">
    <div class="">
        <div class="col-md-6 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Crear Nueva Categoria de Avatar</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('avatar_categ_register') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Nombre</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-md-4 control-label">Descripcion</label>
                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" disabled>
                            </div>
                        </div>
                        
                    </form>
                    <div class="form-group">
                            <div class="col-md-4 col-md-offset-4">
                                <button type="submit" class="btn btn-primary disabled" style="width: 100%">
                                    Registrar
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
$('#edit').on('show.bs.modal',function(event){
     //var category_id = $(this).attr('name');
     var button = $(event.relatedTarget)
     var CategId = button.data('myid')
     var Name = button.data('myname')
     var Description = button.data('mydescription')
     var modal = $(this)
     modal.find('.modal-body #name').val(Name)
     modal.find('.modal-body #description').val(Description)
     modal.find('.modal-body #CategId').val(CategId)
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
