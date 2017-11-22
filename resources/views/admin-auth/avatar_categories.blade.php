@extends('layouts.app')

@section('content')
<style>
.fixposition {
   

    position: fixed;
    top: 70%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    width: 60%;
}
.fixposition2 {
   

    position: fixed;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    width: 60%;
}
</style>
<div class="container">
    <div class="fixposition">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Show</div>

                <div class="panel-body">
                   
                        
                        
                        <div class="col-sm-5">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        

                        

                        <div class="col-sm-5">
                            <label for="password-confirm" class="col-md-4 control-label">Description</label>

                            <div class="">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <div class="">
                                <button type="submit" class="btn btn-primary">
                                   
                                    Submit
                                </button>
                                
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="fixposition2">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Avatar Categories Creator</div>

                <div class="panel-body">
                   
                        
                        
                        <div class="col-sm-5">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        

                        

                        <div class="col-sm-5">
                            <label for="password-confirm" class="col-md-4 control-label">Description</label>

                            <div class="">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <div class="">
                                <button type="submit" class="btn btn-primary">

                                    Submit
                                </button>
                                
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
