@extends('layouts.user')

@section('title')
Event Registration
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/datatables/css/dataTables.bootstrap.css" rel="stylesheet">

<style>
.dataTables_filter input { width: 400px !important }

</style>
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Event Registration</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
    	<p>All the team members should have confirmed IDs before a team can be confirmed.</p>
		<button type="button" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-plus"></span> New Event Registration</button>
    	<br><br>
    	<table class="table table-striped table-hover" id="events_table">
    	    <thead>
    	        <tr>
    	            <th>#</th>
    	            <th>Event</th>
    	            <th>Team Code</th>
    	            <th>Status</th>
    	            <th>Team Members (ID : Registration Status : Name)</th>
    	            <th>No. of Members</th>
    	            <th>Actions</th>
    	        </tr>
    	    </thead>
    	    <tbody>
    	        <?php $i=0; ?>
    	        @foreach ($registrations as $registration)
    	           <?php $i++; ?>
    	           <tr>
    	               <td>{{$i}}</td>
    	               <td>{{$registration->event->name }}</td>
    	               <td>{{$registration->event_code.$registration->team_code }}</td>
    	               <td>@if($registration->confirmation === 1) Confirmed @else Not Confirmed @endif</td>
    	               <td>
    	               		<?php $j=0 ?>
    	               		@foreach($registration->team_members as $member)
    	               			@if($j) <br> @endif
    	               			<?php $j=1 ?>
    	               			{{Config::get('app.id_prefix').$member->details->id }} : {{$member->details->registration_confirm}} : {{$member->details->name}}
    	               		@endforeach
    	               </td>
    	               <td>{{count($registration->team_members) }}</td>
    	               <td>
    	               	<a href="#" class="btn btn-xs btn-default btn-block"><span class="glyphicon glyphicon-ok"></span> Confirm Team</a>
						<a href="#" class="btn btn-xs btn-default btn-block"><span class="glyphicon glyphicon-pencil"></span> Edit Team&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>    	               	
    	               	</td>
    	           </tr>
    	        @endforeach
    	    </tbody>
    	</table>

    </div>
</div>

@stop

@section('scripts')
<script src="{{URL::to('/')}}/bower_components/datatables/js/jquery.dataTables.min.js"></script>
<script src="{{URL::to('/')}}/bower_components/datatables/js/dataTables.bootstrap.min.js"></script>


<script>
    $(function() {
        var table = $('#events_table').DataTable({
                      "columns": [null,
                                  null,
                                  null,
                                  null,
                                  null,
                                  null,
                                  { "orderable": false,  "searchable": false }
                                 ],
                  });

    });
</script>


@stop