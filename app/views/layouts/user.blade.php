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
                <a class="navbar-brand" href="{{ URL::route('manager_dashboard') }}">CMS Manager</a>
                
            </div>

            <ul class="nav navbar-top-links navbar-right hidden-xs">
                <li><a href="{{ URL::route('manager_logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
            </ul>

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        @if(!in_array(Auth::manager()->get()->role,[9,10,11]))
                        <li>
                            <a href="{{ URL::route('manager_dashboard') }}"><i class="fa fa-fw fa-lg fa-dashboard " style="vertical-align: middle;"></i> Dashboard</a>
                        </li>
                        @endif

                        @if(in_array(Auth::manager()->get()->role,[21]))
                        <li>
                            <a href="{{ URL::route('manager_managers') }}"><i class="fa fa-fw fa-lg fa-group" style="vertical-align: middle;"></i> Managers</a>
                        </li>
                        @endif

                        @if(in_array(Auth::manager()->get()->role,[21]))
                        <li>
                            <a href="{{ URL::route('manager_event_categories') }}"><i class="fa fa-fw fa-lg fa-list" style="vertical-align: middle;"></i> Event Categories</a>
                        </li>
                        @endif

                        @if(in_array(Auth::manager()->get()->role,[2]))
                        <li>
                            <a href="{{ URL::route('action_event_redirect_to_edit') }}"><i class="fa fa-fw fa-lg fa-clock-o" style="vertical-align: middle;"></i> Edit Event</a>
                        </li>
                        @endif

                        @if(in_array(Auth::manager()->get()->role,[1,3,5,8,21]))
                        <li>
                            <a href="{{ URL::route('manager_events') }}"><i class="fa fa-fw fa-lg fa-clock-o" style="vertical-align: middle;"></i> Events</a>
                        </li>
                        @endif

                        @if(in_array(Auth::manager()->get()->role,[1,3,5,21]))
                        <li>
                            <a href="{{ URL::route('manager_edit_homepage') }}"><i class="fa fa-fw fa-lg fa-pencil-square-o" style="vertical-align: middle;"></i> Edit Homepage</a>
                        </li>
                        @endif


                        @if(in_array(Auth::manager()->get()->role,[1,3,8,10,11,21]))
                        <li>
                            <a href="{{ URL::route('manager_verify_colleges') }}"><i class="fa fa-fw fa-lg fa-university" style="vertical-align: middle;"></i> Verify Colleges</a>
                        </li>
                        @endif


                        @if(in_array(Auth::manager()->get()->role,[1,3,4,5,6,7,8,21]))
                        <li>
                            <a href="{{ URL::route('manager_student_registrations') }}"><i class="fa fa-fw fa-lg fa-users" style="vertical-align: middle;"></i> Student Registrations</a>
                        </li>
                        @endif

                        @if(in_array(Auth::manager()->get()->role,[1,3,4,5,6,7,8,21]))
                        <li>
                            <a href="{{ URL::route('manager_event_registrations') }}"><i class="fa fa-fw fa-lg fa-trophy" style="vertical-align: middle;"></i> Event Registrations</a>
                        </li>
                        @endif

                        @if(in_array(Auth::manager()->get()->role,[3,4,5,21]))
                        <li>
                            <a href="{{ URL::route('manager_hospitality') }}"><i class="fa fa-fw fa-lg fa-bed" style="vertical-align: middle;"></i> Hospitality</a>
                        </li>
                        @endif


                        @if(in_array(Auth::manager()->get()->role,[9,10,11,21]))
                        <li><hr><a>Registration Software</a></li>

                        @if(in_array(Auth::manager()->get()->role,[11,21]))
                        <li>
                            <a href="{{ URL::route('software_statistics') }}"><i class="fa fa-fw fa-lg fa-area-chart" style="vertical-align: middle;"></i> Statistics</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('software_admin') }}"><i class="fa fa-fw fa-lg fa-diamond" style="vertical-align: middle;"></i> Admin Features</a>
                        </li>
                        <hr>
                        @endif

                        @if(in_array(Auth::manager()->get()->role,[10,11,21]))
                        <li>
                            <a href="{{ URL::route('software_results') }}"><i class="fa fa-fw fa-lg fa-graduation-cap" style="vertical-align: middle;"></i> Results</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('software_block_events') }}"><i class="fa fa-fw fa-lg fa-warning" style="vertical-align: middle;"></i> Block Events</a>
                        </li>
                        <hr>
                        @endif

                        <li>
                            <a href="{{ URL::route('software_student_registration') }}"><i class="fa fa-fw fa-lg fa-paper-plane" style="vertical-align: middle;"></i> Student Registration</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('software_event_registration') }}"><i class="fa fa-fw fa-lg fa-trophy" style="vertical-align: middle;"></i> Event Registration</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('software_workshop_registration') }}"><i class="fa fa-fw fa-lg fa-puzzle-piece" style="vertical-align: middle;"></i> Workshop Registration</a>
                        </li>

                        <hr>

                        <li>
                            <a href="{{ URL::route('software_event_list') }}"><i class="fa fa-fw fa-lg fa-list" style="vertical-align: middle;"></i> Event List</a>
                        </li>

                        <hr>

<!--                    <li>
                            <a href="{{ URL::route('software_hospitality_manager') }}"><i class="fa fa-fw fa-lg fa-bed" style="vertical-align: middle;"></i> Hospitality Manager</a>
                        </li> -->
                        @endif

                        

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
