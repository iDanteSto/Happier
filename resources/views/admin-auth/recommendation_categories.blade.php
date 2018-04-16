@extends('layouts.app')

@section('content')
<form class="form-edit" method="GET" action="{{ url('categ_edit') }}">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Temas de Recomendaciones</h5>
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
                                    if($categs->category_Id == $obj->fk_category_Id){ 
                                        $disponible = false;
                                    }
                                } 
                                if($disponible){ ?>
                                    <tr>
                                        <td><img src="{{$categs->image}}" alt="img" width="60" height="60" class="img-rounded"></td>
                                        <td>{{$categs->description}}</td>
                                        <td>
                                        <a class="btn btn-white btn-bitbucket" data-myname="" data-mydescription="" data-myid="{{$categs->category_Id}}" data-toggle="modal" data-target="#edit"><i class="fa fa-wrench"></i></a>
                                        </td>
                                        <!--Los temas de recomendacion no se deben borrar , ya que son estaticos en la app-->
                                        <!--<td><button type="button" class="btn btn-w-s btn-danger"  name="{{$categs->category_Id}}">Eliminar</button></td> -->
                                    </tr>
                                <?php }else{ ?>
                                    <tr>
                                        <td><img src="{{$categs->image}}" alt="img" width="60" height="60" class="img-rounded"></td>
                                        <td>{{$categs->description}}</td>
                                        <td>
                                        <a class="btn btn-white btn-bitbucket" data-mydescription="{{$categs->description}}" data-myid="{{$categs->category_Id}}" data-toggle="modal" data-target="#edit"><i class="fa fa-wrench"></i></a>
                                        </td>
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
        <h4 class="modal-title">Editar Categoria</h4>
      </div>
      <div class="modal-body">
        <form role="form" class="form-horizontal" method="POST" action="{{ url('categ_update') }}">
                <div class="form-group">
                    <div class="col-md-8">
                    <label>Descripcion</label>  
                    <input id="description" type="text" class="form-control" name="description"  value="" required >
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
    <p>Los Temas de recomendaicon son estaticas en la app , asi que no se podran borrar para asi mantener el ID que tienen designado en esta</p>
    
    <div class="">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Crear un Tema de Recomendacion</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('categ_create') }}" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="Description" class="col-md-4 control-label">Descripcion</label>
                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" required autofocus>
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
                                    Crear Tema
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
//js to show modal with data
$('#edit').on('show.bs.modal',function(event){
     var button = $(event.relatedTarget)
     var CategId = button.data('myid')
     var Description = button.data('mydescription')
     var modal = $(this)
     modal.find('.modal-body #description').val(Description)
     modal.find('.modal-body #CategId').val(CategId)
    
});
//JS to delete categorie
 $(document).on("click", ".btn-danger", function() {
        //   console.log("inside";   <-- here it is
        console.log($(this).attr('name'));
        window.location.href = "/categ_delete/"+$(this).attr('name');
    });

</script>
@endsection
