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

<form class="form-edit" method="GET" action="{{ url('avatar_categ_edit') }}">
<div class="">
    <div class="">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Show</div>

                <div class="panel-body" align="left">
                   
                        
<table>                        
                      
                            

                         <br>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Decription</th>
        <th>Link</th>
        <th>Categ ID</th>
        <th>Commands</th>
        <th>Commands</th>
    </tr>  
                   
                     
                          
@foreach($existingAvatars as $Avatars)
        <tr>
            <td>{{$Avatars->avatar_Id}}</td> <input type="hidden" name="Id" value="{{$Avatars->avatar_Id}}">
            <td>{{$Avatars->name}}</td>
            <td>{{$Avatars->description}}</td>
            <td>{{$Avatars->link}}</td>
            <td>{{$Avatars->fk_avatar_categories_Id}}</td>
            <td><a class="button Edit" name="{{$Avatars->avatar_Id}}" >Edit</a></td>
            <td><a class="button Delete" name="{{$Avatars->avatar_Id}}" >Delete</a></td>
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
                <div class="panel-heading">Avatar Creator</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('CreateAvatar') }}" enctype="multipart/form-data">
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
                               <input type="file" name="file" id="file" multiple>
                              
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Category" class="col-md-4 control-label">Category</label>

                            <div class="col-md-6">
                             
                                <select id="fkCategId" name="fkCategId">
   @foreach($availableCategories as $categs)                                   
  <option value="{{$categs->avatar_categories_Id}}" >{{$categs->name}}</option>
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
