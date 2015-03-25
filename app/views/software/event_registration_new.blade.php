@extends('layouts.user')

@section('title')
New Event Registration
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">New Event Registration</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-6">
        <a href="{{URL::route('software_event_registration')}}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back to Event Registrations</a>
        <br><br>

        <form action="{{URL::route('software_event_registration_new_post')}}" method="POST" role="form">        
            <div class="form-group">
                <label>Event</label>
                <select name="event_code" id="event_select" class="form-control" required="required">
                    <option>-- Select Event --</option>
                    @foreach($events as $event)
                        <option value="{{$event->event_code}}">{{$event->event_code}} : {{$event->team_min}}/{{$event->team_max}} : {{$event->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Team Members</label>;
                <select name="team_members[]" id="team_members_select" style="width:100%;" >
                </select>
            </div>

            <br>
            <button type="submit" class="btn btn-info btn-lg">Create Event Registration <span class="glyphicon glyphicon-chevron-right"></span></button>
        </form>

        <div style="height:200px;"></div>

    </div>
    <div class="col-lg-6 col-md-6">
        @if (Session::get('error'))
            <div class="alert alert-error alert-danger">{{ Session::get('error') }}</div>
        @endif

        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
    </div>
</div>
<!-- /.row -->

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