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

<div class="container">
    <div class="fixposition2">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" >Edit the category</div>

<form class="form-horizontal" method="POST" action="{{ url('categ_update') }}">
                <div class="panel-body">
                   

 
                       
                            
                     
                        <input type="hidden" name="id" value="{{$InfoCateg[0]->category_Id}}">
                        <div class="col-md-5">

                            <label for="description" class="col-md-4 control-label">Description</label>

                            <div class="">
                                <input id="description" type="text" class="form-horizontal" name="description"  value="{{$InfoCateg[0]->description}}" required >
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
@endsection
