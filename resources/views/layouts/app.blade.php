<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name', 'Laravel') }}</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
                        @if (Auth::guard('admin_user')->user())
                            <body class="body">
	<div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="http://res.cloudinary.com/storagefeed/image/upload/v1520231254/logohappier.png" height="100" width="100" />
                             </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">Panel de Administracion Happier</strong>
                             </span> </span> </a>
                            
                        </div>
                        <div class="logo-element" >
                            HAPP
                        </div>
                    </li>
                    <li>
                        <a href="{!!url('admin_login')!!}"><i class="fa fa-desktop"></i> <span class="nav-label">Dashboard</span></a>
                    </li>
                    <li>
                        <a href="{!!url('adminspage')!!}"><i class="fa fa-star"></i> <span class="nav-label">Administradores</span></a>
                    </li>
                    <li class="active">
                        <a><i class="fa fa-database"></i> <span class="nav-label">Contenido</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{!!url('avatar_categories')!!}">Categorias de Avatars</a></li>
                            <li><a href="{!!url('avatars')!!}">Avatars</a></li>
                            <li><a href="{!!url('recommendation_categories')!!}">Categorias de recomendaciones</a></li>
                            <li><a href="{!!url('recommendations')!!}">Recomendaciones</a></li>
                            <li><a href="{!!url('news')!!}">Noticias</a></li>
                        </ul>
                    </li>
                    <li class="active">
                        <a><i class="fa fa-pie-chart"></i> <span class="nav-label">Reportes</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{!!url('metrics_report')!!}">Metricas</a></li>
                        </ul>
                    </li>
                    
                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
        	<div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">Bienvenido a Happier</span>
                        </li>
                        </li>
                        <li>
                            <a href="{{ url('/admin_logout')}}">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                        
                    </ul>

                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper wrapper-content">
						@yield('content')
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <script src="js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="js/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="js/plugins/toastr/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // setTimeout(function() {
            //     toastr.options = {
            //         closeButton: true,
            //         progressBar: true,
            //         showMethod: 'slideDown',
            //         timeOut: 4000
            //     };
            //     toastr.success('Responsive Admin Theme', 'Welcome to INSPINIA');

            // }, 1300);
        });
    </script>
</body>
                     <!--   @elseif (!Auth::guest()) -->
                        @else
                            @yield('content')
                        @endif

</html>
