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
    <div class="fixposition">
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
        <th>Decription</th>
        <th>Commands</th>
    </tr>  
                        </div>

                        <div class="col-md-5">
                            <label for="password-confirm" class="">Description</label>


<?php 
$editableArray = [];
$editlenght = count($checkIfEditable);
/*
foreach ($checkIfEditable as $checkIfEditables)
{
$editableArray = array_fill(0, $editlenght, $checkIfEditables->fk_avatar_categories_Id);
}
*/
//$checkIfEditable->toArray();

//dd($checkIfEditable); 
/*
if (in_array(4, {{$checkIfEditable->fk_avatar_categories_Id}})) {
        echo "si";
    } else {
        echo "no";
    }
*/
?>


@foreach($availableCategories as $categs)

    <tr>
        <td>{{$categs->avatar_categories_Id}}</td> <input type="hidden" name="Id" value="{{$categs->avatar_categories_Id}}">
        <td>{{$categs->name}}</td>
        <td>{{$categs->description}}</td>
        <td><a class="button Edit" name="{{$categs->avatar_categories_Id}}" >Edit</a>
        <br>
        <a class="button Delete" name="{{$categs->avatar_categories_Id}}" >Delete</a>
    </tr> 

@endforeach

                            
                        </div>
</table>
                        
                </div>
            </div>
        </div>
    </div>
</div>
</form>


<div class="container">
    <div class="fixposition2">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Avatar Categories Creator</div>

<form class="form-horizontal" method="POST" action="{{ url('avatar_categ_register') }}">
                <div class="panel-body">
                   
                        
                        
                        <div class="col-md-5">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="">
                                <input id="name" type="text" class="form-horizontal" name="name" value="" required >

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        

                        

                        <div class="col-md-5">
                            <label for="description" class="col-md-4 control-label">Description</label>

                            <div class="">
                                <input id="description" type="text" class="form-horizontal" name="description" required>
                            </div>
                        </div>

                        <div class="col-md-1">
                            
                                <button type="submit" class="btn btn-primary" >
                                
                                    Submit
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
