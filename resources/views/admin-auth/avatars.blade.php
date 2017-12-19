@extends('layouts.app')

@section('content')
<style>
.positioner {
   

    position: fixed;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    width: 60%;
}
</style>
<div class="container">
    <div class="positioner">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Avatar Creator</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('CreateAvatar') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="" required autofocus>

                               
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Description" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" required autofocus>

                               
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Image</label>

                            <div class="col-md-6">
                               <input type="file" name="filename" id="filename">
                              
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Category" class="col-md-4 control-label">Category</label>

                            <div class="col-md-6">
                             
                                <select>
   @foreach($availableCategories as $categs)                                   
  <option value="$categs->avatar_categories_Id">{{$categs->name}}</option>
  @endforeach        
                                </select>
                        
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Upload Avatar
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
