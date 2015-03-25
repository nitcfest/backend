@extends('layouts.user')

@section('title')
Confirm Workshop Registration
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop


@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Confirm Workshop Registration</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
	<div class="col-md-12">
	<a href="{{URL::route('software_workshop_registration')}}" class="btn btn-default "><span class="glyphicon glyphicon-chevron-left"></span> Back to Workshop Registrations</a><br><br>
	
	<p>Ragam registration is not necessary for Workshop registrations. You may edit details for registrations that have not been confirmed.</p>
	</div>
</div>

<div class="modal fade" id="modal-college-select">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Change College</h4>
			</div>
			<div class="modal-body">
				<p>Search for the college name, and select it from the list. Click Confirm to proceed.</p>
				<select id="college_select" style="width:500px;">
				    <option value="0">Loading...</option>
				</select>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-ban-circle"></span> Cancel</button>
				<button type="button" class="btn btn-success" id="action-confirm-college" data-id=""><span class="glyphicon glyphicon-ok"></span> Confirm </button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="row">
    <div class="col-md-6">
    	<br>
    	<form action="{{URL::route('software_workshop_registration_confirm_post')}}" method="POST" role="form">
			<div class="form-group">
				<label>Workshop Name</label>
				<h4>{{$team->event->name}}</h4>
			</div>

			<div class="form-group">
				<label>Team Code</label>
				<h4>{{$team->event->event_code.$team->team_code}}
			</div>

			<div class="form-group">
				<label>Ragam ID @if($team->team_members[0]->details->registration_confirm == 0) <span class="label label-warning">Not Confirmed</span> @else <span class="label label-success">Confirmed</span> @endif</label>
				<h4>{{Config::get('app.id_prefix').$team->owner_id}}</h4>
			</div>
			<div class="form-group">
				<label>Name</label>
				<input name="name" type="text" class="form-control" placeholder="Full Name" value="{{$team->team_members[0]->details->name}}" @if($team->team_members[0]->details->registration_confirm) readonly @endif>
			</div>
			<div class="form-group">
				<label>Email</label>
				<input name="email" type="text" class="form-control" placeholder="Email Address" value="{{$team->team_members[0]->details->email}}" @if($team->team_members[0]->details->registration_confirm) readonly @endif>
			</div>
			<div class="form-group">
				<label>Phone</label>
				<input name="phone" type="text" class="form-control" placeholder="Phone Number" value="{{$team->team_members[0]->details->phone}}" @if($team->team_members[0]->details->registration_confirm) readonly @endif>
			</div>

			<div class="form-group college-container">
				<label>College</label>
				<div class="input-group">
					<input type="text" class="form-control college_name" value="{{$team->team_members[0]->details->college->name}}" readonly>
					<span class="input-group-btn">
						<button type="button" class="btn btn-default action-change-college"  @if($team->team_members[0]->details->registration_confirm) disabled @endif><span class="glyphicon glyphicon-pencil"></span> Change College</button>
				    </span>
			    </div>
				<input type="hidden" name="college_id" value="{{$team->team_members[0]->details->college->id}}">				
			</div>

			<div class="form-group">
				<select name="hospitality_type" class="form-control">
					<option value="0" @if($team->team_members[0]->details->hospitality_type == 0) selected="selected" @endif>No Accomodation</option>
					<option value="1" @if($team->team_members[0]->details->hospitality_type == 1) selected="selected" @endif>Yes, Male</option>
					<option value="2" @if($team->team_members[0]->details->hospitality_type == 2) selected="selected" @endif>Yes, Female</option>
				</select>
			</div>

			
					
			<br>

			<input type="hidden" name="id" value="{{$team->id}}">
    	
    		<button type="submit" class="btn btn-success btn-lg">Confirm Registration <span class="glyphicon glyphicon-chevron-right"></span></button>
    	</form>
    </div>
</div>

@stop


@section('scripts')

<script src="{{URL::to('/')}}/bower_components/select2/select2.min.js"></script>
<script>
$(function() {
	// Uses Select2 for loading college name. 
	//Refer https://select2.github.io/

	function formatCollege (college) {
		if (college.loading) return 'Loading...';

		var markup =  '<div>'+college.name+'</div>';

		return markup;
	}

	function formatCollegeSelection (college) {
		return '<div style="height:28px;">'+ (college.name || '<span style="color:#787878">Select your college...</span>') +'</div>';
	}


	//initialize the college select box
	$('#college_select').select2({
		ajax: {
			url: '{{URL::route('api_college_search')}}',
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
					results: data.colleges
				};
			},
			cache: true
		},
		escapeMarkup: function (markup) { return markup; },
		minimumInputLength: 2,
		templateResult: formatCollege,
		templateSelection: formatCollegeSelection,
	});




	$('.action-change-college').on('click', function(event) {
		event.preventDefault();

		$('#action-confirm-college').data('id', $(this).parents('.registration-details').data('id'));

		$('#modal-college-select').modal('show');
	});


	$('#action-confirm-college').on('click', function(event) {
		event.preventDefault();

		$('.college_name').val($('.select2-selection__rendered div').html());
		$('input[name="college_id"]').val($('#college_select').val());

		$('#modal-college-select').modal('hide');

	});



});
</script>
@stop