@extends('layouts.user')

@section('title')
Team Details
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Team Details</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <a href="{{URL::route('manager_event_registrations')}}" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-chevron-left"></span> Back to Event Registrations</a>
        <br><br>
        <table class="table table-striped">
          <tbody>
            <tr>
              <td>Event Name</td>
              <td>{{$team->event->name}}</td>
            </tr>
            <tr>
              <td>Event Code</td>
              <td>{{$team->event->event_code}}</td>
            </tr>
            <tr>
              <td>Team Code</td>
              <td>{{$team->event->event_code}}{{$team->team_code}}</td>
            </tr>
            <tr>
              <td>Team Owner</td>
              <td>{{$team->owner->name}} ({{Config::get('app.id_prefix').$team->owner->id}})</td>
            </tr>
            <tr>
              <td>Team Members Count</td>
              <td>{{count($team->team_members)}}</td>
            </tr>

          </tbody>
        </table>


        <h3>Team Members</h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ragam ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>College</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($team->team_members as $team_member)
                   <?php $i++; ?>
                   <tr>
                       <td>{{$i}}</td>
                       <td>{{Config::get('app.id_prefix').$team_member->details->id }}</td>
                       <td>{{$team_member->details->name }}</td>
                       <td>{{$team_member->details->email }}</td>
                       <td>{{$team_member->details->phone }}</td>
                       <td>{{$team_member->details->college->name }}</td>
                   </tr>
                @endforeach
            </tbody>
        </table>      


    </div>
</div>
<!-- /.row -->

@stop

@section('scripts')


@stop