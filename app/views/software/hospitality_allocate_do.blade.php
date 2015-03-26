@extends('layouts.user')

@section('title')
Allocate Rooms
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Allocate Rooms</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->



<div class="row">
	<div class="col-md-12">
	<a href="{{URL::route('software_hospitality_allocate')}}" class="btn btn-default "><span class="glyphicon glyphicon-chevron-left"></span> Back to Allocate Rooms</a><br><br>
	</div>
</div>

<div class="row">
    <div class="col-md-10">    	
		<div id="registrations-container">

			<form class="form-inline" role="form" method="POST" id="main-form">
			@foreach($registrations as $registration)

				<div class="well registration-details" data-id="{{$registration->id}}">
					<div class="row">
						<div class="col-md-3">
								<strong>ID<br><h1><strong>{{Config::get('app.id_prefix').$registration->id}}</strong></h1></strong>
						</div>
						<div class="col-md-3">
							<strong>Name</strong><br>{{$registration->name}}
						</div>
						<div class="col-md-3">
							<strong>Email</strong><br>{{$registration->email}}
						</div>
						<div class="col-md-3">
							<strong>Phone</strong><br>{{$registration->phone}}
						</div>
						<div class="col-md-3">
							<br>
							<strong>Hospitality</strong><br>
							@if($registration->hospitality_type == 1) Male
							@elseif($registration->hospitality_type == 2) Female @endif

						</div>
						<div class="col-md-6">
							<br>
							<strong>College</strong><br><span class="data-college">@if($registration->college){{$registration->college->name}}@endif</span>
						</div>

						<div class="result" style="display:none;">
							<div class="col-md-12">&nbsp;
							</div>

							<div class="col-md-3">
								<br>
								<strong>Team Captain</strong><br><span class="data-team_captain">&nbsp;</span>
							</div>
							<div class="col-md-3">
								<br>
								<strong>Location</strong><br><span class="data-location">&nbsp;</span>
							</div>
							<div class="col-md-3">
								<br>
								<strong>Room No</strong><br><span class="data-room_no">&nbsp;</span>
							</div>
							<div class="col-md-3">
								<br>
								<strong>Bed No</strong><br><span class="data-bed_no">&nbsp;</span>
							</div>
						</div>


					</div>


					<div class="inputs">
						<input type="hidden" name="id" value="{{$registration->id}}">
						<br>
						<div class="radio">
							<label>
								<input type="radio" name="team_captain" id="team_captain" value="{{$registration->id}}">
								Team Captain
							</label>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="location" placeholder="Location">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="room_no" placeholder="Room No">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="bed_no" placeholder="Bed No">
						</div>

					</div>
				</div>

			@endforeach
			</form>	
		</div>

		<button type="button" class="btn btn-success btn-lg" id="action-complete-registration"><span class="glyphicon glyphicon-plus"></span> Confirm Registrations</button>
		&nbsp;&nbsp;<img id="loading-animation" style="display:none;" src="{{URL::to('/')}}/css/loading.gif">		
		
		<br><br>

		<div class="alert alert-danger" id="alert-errors" style="display:none;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<div id="errors">

			</div>
		</div>
		<br>

    </div>
</div>

@stop


@section('scripts')

<script src="{{URL::to('/')}}/bower_components/select2/select2.min.js"></script>
<script>
$(function() {
	var _token = '{{{ Session::getToken() }}}';

	var do_confirm = function(){
		$('#action-complete-registration').hide();

		var $this = $('.registration-details').first();

		//Save the data via ajax, show success form, and clear existing one.
		var input_data = {
			id    : $this.find('input[name="id"]').val(),
			location  : $this.find('input[name="location"]').val(),
			room_no : $this.find('input[name="room_no"]').val(),
			bed_no : $this.find('input[name="bed_no"]').val(),
			team_captain: $('input[name="team_captain"]:checked', '#main-form').val(),

			_token: _token
		};


		$('#loading-animation').show();
		console.log(input_data);

		//Save
		$.ajax({
		  url: '{{ URL::route('software_hospitality_allocate_do_ajax') }}',
		  type: 'POST',
		  dataType: 'json',
		  data: input_data,
		  success: function(data, textStatus, xhr) {
		  	$('#loading-animation').hide();

		  	if(data.result == 'success'){
		  		$this.find('.data-team_captain').html(input_data.team_captain);
		  		$this.find('.data-location').html(input_data.location);
		  		$this.find('.data-room_no').html(input_data.room_no);
		  		$this.find('.data-bed_no').html(input_data.bed_no);


		  		$this.find('.result').show();

	  			$this.css({
	  				backgroundColor: '#E1EDC9',
	  			});

		  		$('#alert-errors').hide();
		  		$('#errors').html('');

		  		$this.find('.inputs').hide();

		  		$this.removeClass('registration-details');

		  		if($('.registration-details').length)
		  			do_confirm();

		  	}else{
		  		//show errors

		  		$this.css({
		  			backgroundColor: '#f2dede',
		  		});

		  		$('#alert-errors').show();
		  		$('#errors').html(data.error_messages);

		  		$('#action-complete-registration').show();

		  	}
		  },
		  error: function(xhr, textStatus, errorThrown) {
		  	$('#loading-animation').hide();
		  	alert('An error occured. Make sure you are logged in. Refresh the page and try again.');

		  }

		});

	}



	$('#action-complete-registration').on('click', function(event) {
		event.preventDefault();

		do_confirm();

	});




});
</script>
@stop