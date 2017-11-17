@extends('layouts.app')

@section('content')
<style>
.positioner {
  

    position: fixed;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}
</style>
<!--position: absolute;
  margin: auto;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 200px;
  height: 200px;  -->
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="positioner">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif


                        @if (Auth::guard('admin_user')->user())
                            
                        <img src="https://www.caremonkey.com/wp-content/uploads/2015/09/CareMonkey-Admin.png" alt="Dude" height="300" width="300">
                            
                        @elseif (!Auth::guest())
                             You shouldn't be here
                        @else
                        @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
