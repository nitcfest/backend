@extends('layouts.user')

@section('title')
Edit Student Registration
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit Student Registration</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->


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
	<div class="col-md-12">
	<a href="{{URL::route('software_admin')}}" class="btn btn-default "><span class="glyphicon glyphicon-chevron-left"></span> Back to Admin Features</a><br><br>
	</div>
</div>

<div class="row">
    <div class="col-md-10">    	

		<div id="registrations-container">
			<div class="well registration-details" data-id="{{$registration->id}}">
				<form class="form-inline" role="form" method="POST" action="{{URL::route('software_admin_save_registration')}}">

					<input type="hidden" name="id" value="{{$registration->id}}">

    				<div class="form-group">
    					<input type="text" class="form-control" value="{{Config::get('app.id_prefix').$registration->id}}" readonly>
    				</div>
    				<div class="form-group">
    					<input style="width: 300px;" type="text" class="form-control" name="name" placeholder="Name" value="{{$registration->name}}">
    				</div>
    				<div class="form-group">
    					<input style="width: 200px;" type="text" class="form-control" name="email" placeholder="Email Address" value="{{$registration->email}}">
    				</div>
    				<div class="form-group">
    					<input type="text" class="form-control" name="phone" placeholder="Phone" value="{{$registration->phone}}">
    				</div>

    				<br><br>
    				<div class="form-group">
    					<select name="hospitality_type" class="form-control">
    						<option value="0" @if($registration->hospitality_type == 0) selected="selected" @endif>No Accomodation</option>
    						<option value="1" @if($registration->hospitality_type == 1) selected="selected" @endif>Yes, Male</option>
    						<option value="2" @if($registration->hospitality_type == 2) selected="selected" @endif>Yes, Female</option>
    					</select>
    				</div>

    				<div class="form-group">
    					<select name="hospitality_confirm" class="form-control">
    						<option value="1" @if($registration->hospitality_confirm == 1) selected="selected" @endif>Hospitality Confirmed</option>
    						<option value="0" @if($registration->hospitality_confirm == 0) selected="selected" @endif>Hospitality Not Confirmed</option>
    					</select>
    				</div>

    				<div class="form-group">
    					<select name="registration_confirm" class="form-control">
    						<option value="1" @if($registration->registration_confirm == 1) selected="selected" @endif>Registration Confirmed</option>
    						<option value="0" @if($registration->registration_confirm == 0) selected="selected" @endif>Registration Not Confirmed</option>
    					</select>
    				</div>
    				<br><br>

    				<div class="form-group college-container">
    					<input type="text" class="form-control college_name" value="{{$registration->college->name}}" style="width:500px;" readonly>
    					<button type="button" class="btn btn-default action-change-college"><span class="glyphicon glyphicon-pencil"></span> Change College</button>
    					<input type="hidden" name="college_id" value="{{$registration->college->id}}">
    				</div>
					<br><br><br>
					<button type="submit" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-floppy-disk"></span> Save Changes</button>
    			</form>
			</div>

		</div>

		
		
	    @if (Session::get('error'))		
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{{ Session::get('error') }}
			</div>
	    @endif



    </div>

</div>

@stop


@section('scripts')

<script src="{{URL::to('/')}}/bower_components/select2/select2.min.js"></script>
<script>
$(function() {
	var _token = '{{{ Session::getToken() }}}';

	$('.action-change-college').on('click', function(event) {
		event.preventDefault();

		$('#action-confirm-college').data('id', $(this).parents('.registration-details').data('id'));

		$('#modal-college-select').modal('show');
	});


	$('#action-confirm-college').on('click', function(event) {
		event.preventDefault();

		var id = $(this).data('id');

		$('.registration-details').filter(function() {

			if($(this).data('id') == id){
				$(this).find('.college_name').val($('.select2-selection__rendered div').html());
				$(this).find('input[name="college_id"]').val($('#college_select').val());
			}

		});

		$('#modal-college-select').modal('hide');

	});

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

	var init_college = function(reset){

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
	};

	init_college();

});
</script>
@stop