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

<div class="">
    <div class="">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Avatar Creator</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('avatar_update') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <input type="hidden" name="avatar_id" value="{{$avatarInfo[0]->avatar_Id}}">

                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{$avatarInfo[0]->name}}" required autofocus>

                               
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Description" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" value="{{$avatarInfo[0]->description}}" required autofocus>
                              
                            </div>
                        </div>

                        

                        <div class="form-group">
                            <label for="Category" class="col-md-4 control-label">Category</label>
<?php $currentCategName = DB::table('avatar_categories')->select('name')->where('avatar_categories_Id', $avatarInfo[0]->fk_avatar_categories_Id)->get(); ?>
                            <div class="col-md-6">
                             
                                <select id="fkCategId" name="fkCategId">
                                <option value="{{$avatarInfo[0]->fk_avatar_categories_Id}}" selected >{{$currentCategName[0]->name}}</option>
   @foreach($availableCategories as $categs)                                   
  <option value="{{$categs->avatar_categories_Id}}" >{{$categs->name}}</option>
  @endforeach        
                                </select>
                        
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Avatar
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
