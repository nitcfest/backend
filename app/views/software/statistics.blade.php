@extends('layouts.user')

@section('title')
Statistics
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Statistics</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-paper-plane fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$registration}}</div>
                        <div class="big">Confirmed Registration</div>
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
                        <i class="fa fa-bed fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$hospitality}}</div>
                        <div class="big">Confirmed Hospitality</div>
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
                        <div class="huge">{{$events}}</div>
                        <div class="big">Confirmed Event Teams</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-puzzle-piece fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$workshops}}</div>
                        <div class="big">Confirmed Workshop Teams</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
    


    </div>
</div>

@stop

@section('scripts')


@stop