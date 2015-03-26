@extends('layouts.user')

@section('title')
Event List
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Event List</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-3">
    	<h4>Select an event</h4>
    	<form action="{{URL::route('software_event_list_post')}}" method="POST" role="form">        
    	    <div class="form-group">
    	        <select name="event_code" id="event_select" class="form-control" required="required">
    	            <option>-- Select Event --</option>
    	            @foreach($events as $event_item)
    	                <option value="{{$event_item->event_code}}">{{$event_item->event_code}} : {{$event_item->name}}</option>
    	            @endforeach
    	        </select>
    	    </div>
    	    <button type="submit" class="btn btn-info btn-lg">Show Event List <span class="glyphicon glyphicon-chevron-right"></span></button>
    	</form>
    </div>
</div>
<div class="row">
	<div class="col-md-12">
    	@if(isset($teams))
        <br><br>
	    	<a target="_blank" href="{{URL::route('software_event_list_print')}}?event_code={{$event->event_code}}" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-print"></span> Print-Friendly</a>
        
        <h2>{{$event->name}}</h2>
        @if(count($teams) > 0)
	    		<div id="print-section">
	    		@foreach($teams as $team)
	    			<h3>{{$team->event_code.$team->team_code}}</h3>
	    			<table class="table table-bordered table-condensed table-hover">
	    			    <thead>
	    			        <tr>
	    			            <th style="width: 3%">#</th>
	    			            <th style="width: 10%">Ragam ID</th>
	    			            <th style="width: 20%">Name</th>
	    			            <th style="width: 20%">Email</th>
	    			            <th style="width: 12%">Phone</th>
	    			            <th style="width: 36%">College</th>
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
	    		@endforeach
          

          <p>Start On-Spot Registrations from: {{$on_spot}}</p>

	    		</div>
	    	@else
	    	<h4>No confirmed teams for this event.</h4>
	    	@endif
    	@endif

    </div>
</div>

@stop

@section('scripts')
<script src="{{URL::to('/')}}/bower_components/select2/select2.min.js"></script>
<script>
  $(function() {

    $('#event_select').select2();

    // Uses Select2 for loading TEAM MEMBERS
    //Refer https://select2.github.io/

    function formatTeamMembers (user) {
        if (user.loading) return 'Loading...';

        var markup =  '<div>('+user.full_id+') '+user.name+'</div>';

        return markup;
      }

      function formatTeamMembersSelection (user) {
        return user.name;
      }

    //initialize the team member select box.
    $("#team_members_select").select2({
      ajax: {
        url: '{{URL::to('/')}}' + "/api/users",
        dataType: 'jsonp',
        delay: 250,
        data: function (params) {
          return {
                q: params.term,
                page: params.page
              };
        },
        processResults: function (data, page) {
            return {
              results: data.users
            };
        },
        cache: true
      },
      escapeMarkup: function (markup) { return markup; },
      minimumInputLength: 2,
      templateResult: formatTeamMembers,
      templateSelection: formatTeamMembersSelection,

      placeholder : 'Team',
      multiple:true,    
      maxSelectionLength : 20//Maximum number of team members that can be selected.
                 //Change this depending on team size!!!
    });

  });
</script>

@stop