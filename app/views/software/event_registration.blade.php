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
		<a href="{{URL::route('software_event_registration_new')}}" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-plus"></span> New Event Registration</a> &nbsp;<img id="loading-animation" style="display:none;" src="{{URL::to('/')}}/css/loading.gif"> 
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
    	    <tbody id="data-body">
    	        <?php $i=0; ?>
    	        @foreach ($registrations as $registration)
    	           <?php $i++; ?>
    	           <tr @if($registration->confirmation == 1) style="background: #E1EDC9;" @endif>
    	               <td>{{$i}}</td>
    	               <td>{{$registration->event->name }}</td>
    	               <td>{{$registration->event_code.$registration->team_code }}</td>
    	               <td class="registration-status">@if($registration->confirmation === 1) Confirmed @else Not Confirmed @endif</td>
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
    	               	@if($registration->confirmable==1 && $registration->confirmation != 1)<button type="button" data-team_id="{{$registration->id}}" class="action-confirm-team btn btn-xs btn-default btn-block"><span class="glyphicon glyphicon-ok"></span> Confirm Team</button>@endif
						<a href="{{ URL::route('software_event_registration_details',$registration->id) }}" class="btn btn-xs btn-default btn-block"><span class="glyphicon glyphicon-stats"></span> View Details </a>
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

        $('#data-body').on('click', '.action-confirm-team', function(event) {
            event.preventDefault();

            var team_id = $(this).data('team_id');
            var btn = $(this);

            $('#loading-animation').show();

            $.ajax({
              url: '{{ URL::route('software_event_registration_confirm') }}',
              type: 'POST',
              dataType: 'json',
              data: { team_id: team_id },
              success: function(data, textStatus, xhr) {
                $('#loading-animation').hide();

                if(data.result == 'success'){
                    //Show success
                    btn.parents('tr').css({
                        backgroundColor: '#E1EDC9',
                    });

                    btn.parents('tr').find('td.registration-status').html('Confirmed');
                    btn.parents('td').find('.action-confirm-team').remove();
                }else{
                    //show errors
                    alert('Could not confirm team. Refresh page and try again.');
                }

              },
              error: function(xhr, textStatus, errorThrown) {
                $('#loading-animation').hide();
                alert('An error occured. Make sure you are logged in. Refresh the page and try again.');

              }

            });

        });


    });
</script>


@stop