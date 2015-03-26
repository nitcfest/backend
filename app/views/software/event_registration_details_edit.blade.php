@extends('layouts.user')

@section('title')
Edit Event Registration
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit Event Registration</h1>
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
                    <th>Action</th>
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
                       <td>@if($team_member->details->college){{$team_member->details->college->name }}@endif</td>
                       <td><a href="{{URL::route('software_event_registration_remove_member')}}?team_id={{$team->id}}&member_id={{$team_member->details->id}}" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span> Remove from Team</a></td>
                   </tr>
                @endforeach
            </tbody>
        </table>   


        <div class="well">
          <h4>Add Team Members</h4>

          <form action="{{URL::route('software_event_registration_add_members')}}" method="POST" role="form">
            <input type="hidden" name="team_id" value="{{$team->id}}">
            <select name="team_members[]" id="team_members_select" style="width:500px;" >
            </select>
            
            <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span> Add Team Members</button>
          </form>
        </div>

        <div style="height:200px;"></div>


    </div>
</div>
<!-- /.row -->

@stop

@section('scripts')
<script src="{{URL::to('/')}}/bower_components/select2/select2.min.js"></script>
<script>
  $(function() {
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