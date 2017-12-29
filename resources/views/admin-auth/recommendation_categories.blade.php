@extends('layouts.app')

@section('content')

<style>
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<form class="form-edit" method="GET" action="{{ url('avatar_categ_edit') }}">
<div class="container">
    <div class="">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Show</div>

                <div class="panel-body" align="left">
                   
                        
<table>                        
                        <div class="col-md-5">
                            <label for="name" class="">Name</label>

                         <br>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th></th>
        <th>Commands</th>
    </tr>  
                        </div>

                        <div class="col-md-5">
                            <label for="password-confirm" class="">Description</label>
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
            <td>{{$categs->category_Id}}</td> <input type="hidden" name="Id" value="{{$categs->category_Id}}">
            <td>{{$categs->description}}</td>
            <td><img src="{{$categs->image}}" alt="img" width="100" height="100"></td>

            <td><a class="button Edit" name="{{$categs->category_Id}}" >Edit</a></td>
            <td><a class="button Delete" name="{{$categs->category_Id}}" >Delete</a></td>
        </tr>
    <?php }else{ ?>
        <tr>
            <td>{{$categs->category_Id}}</td> <input type="hidden" name="Id" value="{{$categs->category_Id}}">
            <td>{{$categs->description}}</td>
            <td><img src="{{$categs->image}}" alt="img" width="100" height="100"></td>

            <td><a class="button Edit" name="{{$categs->category_Id}}" >Edit</a></td>
            <td><img src="http://www.endlessicons.com/wp-content/uploads/2012/12/lock-icon-614x460.png" alt="lock" width="42" height="42"></td>
        </tr>
    <?php 
        } 
    ?>
@endforeach


                            
                        </div>
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
                    <form class="form-horizontal" method="POST" action="{{ url('categ_create') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
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
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Upload Category
                                </button>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

$(document).on("click", ".Edit", function() {
//   console.log("inside";   <-- here it is
    console.log($(this).attr('name'));
    window.location.href = "/avatar_categ_edit/"+$(this).attr('name');
 });


$(document).on("click", ".Delete", function() {
//   console.log("inside";   <-- here it is
    console.log($(this).attr('name'));
    window.location.href = "/avatar_categories_delete/"+$(this).attr('name');
 });

</script>
@endsection
