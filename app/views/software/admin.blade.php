@extends('layouts.user')
    
@section('title')
Admin Features
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Admin Features</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-5">

        <h2>Edit Confirmed Registration</h2>
        <form action="{{URL::route('software_admin_edit_registration')}}" method="GET" role="form">        
            <div class="form-group">
                <select name="id" id="registration_select" class="form-control" required="required">
                    <option>-- Select Registration --</option>
                    @foreach($confirmed_registrations as $registration)
                        <option value="{{$registration->id}}">({{Config::get('app.id_prefix').$registration->id}}) {{$registration->name}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-warning btn-lg">Edit Registration <span class="glyphicon glyphicon-chevron-right"></span></button>
        </form>

        <hr>

        <h2>Unconfirm Team</h2>
        <form action="{{URL::route('software_admin_unconfirm_team')}}" method="POST" role="form">        
            <div class="form-group">
                <select name="team_id" id="team_select" class="form-control" required="required">
                    <option>-- Select Team --</option>
                    @foreach($confirmed_teams as $team)
                        <option value="{{$team->id}}">{{$team->event_code.$team->team_code}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-danger btn-lg">Unconfirm Team <span class="glyphicon glyphicon-chevron-right"></span></button>
        </form>

        @if (Session::get('success'))     
            <br><br>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('success') }}
            </div>
        @endif

    </div>
</div>

@stop

@section('scripts')
<script src="{{URL::to('/')}}/bower_components/select2/select2.min.js"></script>
<script>
  $(function() {

    $('#team_select, #registration_select').select2();
  });
</script>

@stop