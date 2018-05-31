@extends('layouts.app')

@section('content')

@if (Auth::guard('admin_user')->user()->level == '1')
<form class="form-edit" method="GET" action="{{ url('avatar_categ_edit') }}">
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
                            @foreach($availableRecommendations as $Recommendation)
                            <tr>
                                <td><img src="{{$Recommendation->image}}" alt="img" width="60" height="60" class="img-rounded"></td>
                                <td>{{$Recommendation->name}}</td>
                                <td>{{$Recommendation->description}}</td>
                                <?php $Typename = DB::table('recommendation_type')->select('name')->where('recommendation_type_Id', $Recommendation->FK_TYPE)->get(); ?>           
                                <td><strong>{{$Typename[0]->name}}</strong></td>
                                <?php $Categname = DB::table('category')->select('description')->where('category_Id', $Recommendation->fk_category_Id)->get(); ?>           
                                <td><strong>{{$Categname[0]->description}}</strong></td>
                                <?php $Timeofdayname = DB::table('timesofday')->select('description')->where('TimesofDay_Id', $Recommendation->timeofday)->get(); ?>         
                                <td>{{$Timeofdayname[0]->description}}</td>
                                <td>
                                    <a class="btn btn-white btn-bitbucket" data-myname="{{$Recommendation->name}}" data-mydescription="{{$Recommendation->description}}" data-myid="{{$Recommendation->recommendation_Id}}" data-toggle="modal" data-target="#editRecommendation"><i class="fa fa-wrench"></i></a>
                                </td>
                                <td><button type="button" class="btn btn-w-s btn-danger"  name="{{$Recommendation->recommendation_Id}}">Eliminar</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
<!--modal-->
<div id="editRecommendation" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Editar la Recomendacion</h4>
    </div>
    <div class="modal-body">
        <form role="form" class="form-horizontal" method="POST" action="{{ url('recommendation_update') }}">
            <div class="form-group">
                <div class="col-md-8">
                    <label>Nombre</label> 
                    <input id="name" type="text" class="form-control" name="name" value="" required>  
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-8">    
                    <label for="textarea">Descripcion</label>
                    <textarea class="form-control" id="description" rows="5" name="description" value="" required ></textarea>
                </div>
            </div>
            <div class="form-group">
                            <div class="col-md-8">
                                <label for="Category">Tipo de Recomendacion</label>
                                <select class="form-control m-b" id="FK_TYPE" name="FK_TYPE">
                                    @foreach($availableRecommendationTypes as $types)                                   
                                    <option value="{{$types->recommendation_type_Id}}" >{{$types->name}}</option>
                                    @endforeach        
                                </select>
                            </div>
                        </div>
            <div class="form-group">
                <div class="col-md-8">
                    <label for="Category">Tema</label>
                    <select class="form-control m-b" id="fkCategId" name="fkCategId">
                      @foreach($availableCategories as $categs)                                   
                      <option value="{{$categs->category_Id}}" >{{$categs->description}}</option>
                      @endforeach     
                  </select>
              </div>
          </div>
          <input id="RecomId" type="hidden" class="form-control" name="RecomId"  value="">
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
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Crear Recomendacion</b></div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('recommendation_create') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-8">
                            <label for="name">Nombre</label>
                                <input id="name" type="text" class="form-control" name="name" value="" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">    
                                <label for="textarea" >Descripcion</label>
                                <textarea class="form-control" id="description" rows="5" name="description" value="" required ></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <label for="Category">Tipo de Recomendacion</label>
                                <select class="form-control m-b" id="FK_TYPE" name="FK_TYPE">
                                    @foreach($availableRecommendationTypes as $types)                                   
                                    <option value="{{$types->recommendation_type_Id}}" >{{$types->name}}</option>
                                    @endforeach        
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <label for="Category">Tema</label>
                                <select class="form-control m-b" id="fkCategId" name="fkCategId">
                                    @foreach($availableCategories as $categs)                                   
                                    <option value="{{$categs->category_Id}}" >{{$categs->description}}</option>
                                    @endforeach        
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <label for="Frequency">Tiempo del dia</label>
                                <select  class="form-control m-b" id="TimesofDay_Id" name="TimesofDay_Id">
                                  @foreach($availableTimesofDay as $times)                                   
                                  <option value="{{$times->TimesofDay_Id}}" >{{$times->description}}</option>
                                  @endforeach        
                              </select>
                          </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-8">
                        <label for="imagen">Imagen</label>
                           <input type="file" name="file" id="file" multiple>      
                       </div>
                   </div>
                   <div class="form-group">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-w-m btn-warning" style="width: 100%">
                            Crear Recomendacion
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
<form class="form-edit" method="GET" action="{{ url('avatar_categ_edit') }}">
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
                            @foreach($availableRecommendations as $Recommendation)
                            <tr>
                                <td><img src="{{$Recommendation->image}}" alt="img" width="60" height="60" class="img-rounded"></td>
                                <td>{{$Recommendation->name}}</td>
                                <td>{{$Recommendation->description}}</td>
                                <?php $Categname = DB::table('category')->select('description')->where('category_Id', $Recommendation->fk_category_Id)->get(); ?>           
                                <td><strong>{{$Categname[0]->description}}</strong></td>
                                <?php $Timeofdayname = DB::table('timesofday')->select('description')->where('TimesofDay_Id', $Recommendation->timeofday)->get(); ?>         
                                <td>{{$Timeofdayname[0]->description}}</td>
                                <td>
                                    <a class="btn btn-white btn-bitbucket" data-myname="{{$Recommendation->name}}" data-mydescription="{{$Recommendation->description}}" data-myid="{{$Recommendation->recommendation_Id}}" data-toggle="modal" data-target="#editRecommendation"><i class="fa fa-wrench"></i></a>
                                </td>
                                <td><button type="button" class="btn btn-primary disabled"  name="{{$Recommendation->recommendation_Id}}">Eliminar</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
<!--modal-->
<div id="editRecommendation" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Editar la Recomendacion</h4>
    </div>
    <div class="modal-body">
        <form role="form" class="form-horizontal" method="POST" action="{{ url('recommendation_update') }}">
            <div class="form-group">
                <div class="col-md-8">
                    <label>Nombre</label> 
                    <input id="name" type="text" class="form-control" name="name" value="" disabled>  
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-8">    
                    <label for="textarea">Descripcion</label>
                    <textarea class="form-control" id="description" rows="5" name="description" value="" disabled ></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-8">
                    <label for="Category">Categoria</label>
                    <select class="form-control m-b" id="fkCategId" name="fkCategId" disabled>
                      @foreach($availableCategories as $categs)                                   
                      <option value="{{$categs->category_Id}}" >{{$categs->description}}</option>
                      @endforeach     
                  </select>
              </div>
          </div>
          <input id="RecomId" type="hidden" class="form-control" name="RecomId"  value="" disabled>
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

<div class="">
    <div class="">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Crear Recomendacion</b></div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('recommendation_create') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-8">
                            <label for="name">Nombre</label>
                                <input id="name" type="text" class="form-control" name="name" value="" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">    
                                <label for="textarea" >Descripcion</label>
                                <textarea class="form-control" id="description" rows="5" name="description" value="" disabled></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <label for="Category">Categoria</label>
                                <select class="form-control m-b" id="fkCategId" name="fkCategId" disabled>
                                    @foreach($availableCategories as $categs)                                   
                                    <option value="{{$categs->category_Id}}" >{{$categs->description}}</option>
                                    @endforeach        
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <label for="Frequency">Tiempo del dia</label>
                                <select  class="form-control m-b" id="TimesofDay_Id" name="TimesofDay_Id" disabled>
                                  @foreach($availableTimesofDay as $times)                                   
                                  <option value="{{$times->TimesofDay_Id}}" >{{$times->description}}</option>
                                  @endforeach        
                              </select>
                          </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-8">
                        <label for="imagen">Imagen</label>
                           <input type="file" name="file" id="file" multiple disabled>      
                       </div>
                   </div>
                   </form>
                   <div class="form-group">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary disabled" style="width: 100%">
                            Crear Recomendacion
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
$('#editRecommendation').on('show.bs.modal',function(event){
 var button = $(event.relatedTarget)
 var RecomId = button.data('myid')
 var Name = button.data('myname')
 var Description = button.data('mydescription')
 var modal = $(this)
 modal.find('.modal-body #name').val(Name)
 modal.find('.modal-body #description').val(Description)
 modal.find('.modal-body #RecomId').val(RecomId)

});
//JS to delete categorie
$(document).on("click", ".btn-danger", function() {
        //   console.log("inside";   <-- here it is
        console.log($(this).attr('name'));
        window.location.href = "/recommendation_delete/"+$(this).attr('name');
    });
</script>
@endsection
