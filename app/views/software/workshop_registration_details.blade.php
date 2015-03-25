@extends('layouts.user')

@section('title')
Workshop Registration
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop


@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Workshop Registration</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
	<div class="col-md-12">
		<a href="{{URL::route('software_workshop_registration')}}" class="btn btn-default "><span class="glyphicon glyphicon-chevron-left"></span> Back to Workshop Registrations</a><br><br>
	</div>
</div>


<div class="row">
    <div class="col-md-4">
    	<div class="form-group">
    		<label>Workshop Name</label>
    		<h4>{{$team->event->name}}</h4>
    	</div>
    	<br>

    	<div class="form-group">
    		<label>Workshop Registration Status</label>
    		<h4>@if($team->confirmation == 1) Confirmed @else Not Confirmed @endif</h4>
    	</div>	
    	<br>

    	<div class="form-group">
    		<label>Team Code</label>
    		<h4>{{$team->event->event_code.$team->team_code}}</h4>
    	</div>
    	<br>

    	@if($team->confirmation == 0)
    		<a href="{{URL::route('software_workshop_registration_confirm')}}?id={{$team->id}}" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-ok"></span> Confirm Workshop Registration</a>
    	@endif
    </div>
    <div class="col-md-4">


		<div class="form-group">
			<label>Ragam ID @if($team->team_members[0]->details->registration_confirm == 0) <span class="label label-warning">Not Confirmed</span> @else <span class="label label-success">Confirmed</span> @endif</label>
			<h4>{{Config::get('app.id_prefix').$team->owner_id}}</h4>
		</div>
		<br>

		<div class="form-group">
			<label>Name</label>
			<h4>{{$team->team_members[0]->details->name}}</h4>
		</div>
		<br>

		<div class="form-group">
			<label>Email</label>
			<h4>{{$team->team_members[0]->details->email}}</h4>
		</div>
		<br>

		<div class="form-group">
			<label>Phone</label>
			<h4>{{$team->team_members[0]->details->phone}}</h4>
		</div>
		<br>

		<div class="form-group">
			<label>College</label>
			<h4>{{$team->team_members[0]->details->college->name}}</h4>
		</div>	
		<br>


		<br>
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