@extends('layouts.user')

@section('title')
Event Registration Details
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Event Registration Details</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <a href="{{URL::route('software_event_registration')}}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back to Event Registrations</a>
        <br><br>
        <table class="table table-striped">
          <tbody>
            <tr>
              <td>Event Name</td>
              <td>{{$team->event->name}}</td>
            </tr>
            <tr>
              <td>Registration Status</td>
              <td>@if($team->confirmation == 1) Confirmed @else Not Confirmed @endif</td>
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
              <td>Team Members Count</td>
              <td>{{count($team->team_members)}}</td>
            </tr>
            <tr>
              <td>Event Team Min/Max</td>
              <td>{{$team->event->team_min}}/{{$team->event->team_max}}</td>
            </tr>

          </tbody>
        </table>

        @if($team->confirmation == 0 && $team->confirmable == 1)
        	<a href="{{URL::route('software_event_registration_confirm_get')}}?id={{$team->id}}" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-ok"></span> Confirm Event Registration</a>
        @endif
        @if($team->confirmation == 0)
          <a href="{{URL::route('software_event_registration_details_edit',$team->id)}}" class="btn btn-lg btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit Team</a>
        @endif


        <h3>Team Members</h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ragam ID</th>
                    <th>Status</th>
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
                       <td>@if($team_member->details->registration_confirm == 1) Confirmed @else Not Confirmed @endif</td>
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