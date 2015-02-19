<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    @section('description')
    <meta name="description" content="">
    @show

    <title>
        @section('title')
        CMS Manager
        @show
    </title>

    @section('head')
    @show

    <!-- Bootstrap Core CSS -->
    <link href="{{URL::to('/')}}/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{URL::to('/')}}/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{URL::to('/')}}/css/main.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{URL::to('/')}}/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <div id="ajax-loading"></div>

    @section('modals')
    @show

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ URL::to('/') }}">CMS Manager</a>
                
            </div>

            <ul class="nav navbar-top-links navbar-right hidden-xs">
                <li><a href="{{ URL::route('manager_logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
            </ul>

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="{{ URL::route('manager_dashboard') }}"><i class="fa fa-fw fa-lg fa-dashboard " style="vertical-align: middle;"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('manager_managers') }}"><i class="fa fa-fw fa-lg fa-group" style="vertical-align: middle;"></i> Managers</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('manager_events') }}"><i class="fa fa-fw fa-lg fa-clock-o" style="vertical-align: middle;"></i> Events</a>
                        </li>
                        <li>
                            <a href="{{ URL::to('devices') }}"><i class="fa fa-fw fa-lg fa-pencil-square-o" style="vertical-align: middle;"></i> Edit Homepage</a>
                        </li>
                        <li class="visible-xs">
                            <a href="{{ URL::route('manager_logout') }}"><i class="fa fa-fw fa-lg fa-sign-out" style="vertical-align: middle;"></i> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div id="page-wrapper">
            @section('content')
            @show
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{URL::to('/')}}/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{URL::to('/')}}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{URL::to('/')}}/bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{URL::to('/')}}/js/main.js"></script>

    @section('scripts')
    @show
    
</body>

</html>
