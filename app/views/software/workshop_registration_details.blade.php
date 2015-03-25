@extends('layouts.user')

@section('title')
Workshop Registration Details
@stop

@section('head')

@stop


@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Workshop Registration Details</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
	<div class="col-md-12">
	<a href="{{URL::route('software_workshop_registration')}}" class="btn btn-default "><span class="glyphicon glyphicon-chevron-left"></span> Back to Workshop Registrations</a><br><br>
	
	<p>Ragam registration confirmation is not necessary for Workshop registrations.</p>
	</div>
</div>

<div class="row">
    <div class="col-md-12">
		<table class="table table-striped">
		  <tbody>
		    <tr>
		      <td>Workshop Name</td>
		      <td>{{$team->event->name}}</td>
		    </tr>
		    <tr>
		      <td>Workshop Registration Status</td>
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

		@if($team->confirmation == 0)
			<a href="{{URL::route('software_workshop_registration_confirm')}}?id={{$team->id}}" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-ok"></span> Confirm Workshop Registration</a>
		@endif
		<br>

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

@stop


@section('scripts')


@stop