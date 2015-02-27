@extends('layouts.user')

@section('title')
CMS Manager
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

@if(Auth::manager()->get()->role !=2)
<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-clock-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$events_count}}</div>
                        <div class="big">Active Events</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$managers_count}}</div>
                        <div class="big">Managers</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-trophy fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$teams_count}}</div>
                        <div class="big">Event Registrations</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$registrations_count}}</div>
                        <div class="big">Students Registered</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        @if(Auth::manager()->get()->role ==2)
        You may edit your event by clicking on the Edit Event button. Please contact the administrator if you encounter any bugs/errors.
        <br><br>

        <h3>Your event code: {{ $event_code }}</h3>
        @endif
    </div>
</div>

@stop

@section('scripts')
@stop