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

<div class="fixposition2">
    <div class="">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Recommendation</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('recommendation_update') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <input type="hidden" name="recommendation_Id" value="{{$recommendationInfo[0]->recommendation_Id}}">

                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{$recommendationInfo[0]->name}}" required autofocus>

                               
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Description" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" value="{{$recommendationInfo[0]->description}}" required autofocus>
                              
                            </div>
                        </div>

                        

                        <div class="form-group">
                            <label for="Category" class="col-md-4 control-label">Category</label>
<?php $currentCategName = DB::table('category')->select('description')->where('category_Id', $recommendationInfo[0]->fk_category_Id)->get(); ?>
                            <div class="col-md-6">
                             
                                <select id="fkCategId" name="fkCategId">
                                <option value="{{$recommendationInfo[0]->fk_category_Id}}" selected >{{$currentCategName[0]->description}}</option>
   @foreach($availableCategories as $categs)                                   
  <option value="{{$categs->category_Id}}" >{{$categs->description}}</option>
  @endforeach        
                                </select>
                        
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Recommendation
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
