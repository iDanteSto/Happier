@extends('layouts.app')

@section('content')
<body class="gray-bg">
<div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
            <img src="http://res.cloudinary.com/storagefeed/image/upload/v1520231254/logohappier.png" alt="Happierlogo" height="150" width="150">
            </div>
            <h3>Panel de Administracion Happier</h3>
            <p>Para acceder al panel de administracion de Happier , porfavor ingresa tus credenciales.
            </p>
            
            <form class="m-t" role="form" method="POST" action="{{ url('admin_login') }}">
                {{ csrf_field() }}
                <div class="form-group">
                     <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="form-group">
                    <input id="password" type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
            </form>
        </div>
    </div>
</body>
@endsection
