@extends('layouts.user')

@section('title')
Student Registration
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Student Registration</h1>
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
    <div class="col-md-10">
    	<div id="registration-success-container">
    		<div class="well registration-success" style="background:#E1EDC9; display:none; " data-hospitality="">
    			<div class="row">
    				<div class="col-md-3">
    					<strong>ID<br><h1><strong>{{Config::get('app.id_prefix')}}<span class="data-id">10001</span></strong></h1></strong>
    				</div>
    				<div class="col-md-3">
    					<strong>Name</strong><br><span class="data-name">Saneem</span>
    				</div>
    				<div class="col-md-3">
    					<strong>Email</strong><br><span class="data-email">xaneem@gmail.com</span>
    				</div>
    				<div class="col-md-3">
    					<strong>Phone</strong><br><span class="data-phone">9809639109</span>
    				</div>
    				<!-- <div class="col-md-12" style="height:15px;"></div> -->
    				<div class="col-md-3">
    					<br>
    					<strong>Hospitality</strong><br><span class="data-hospitality">No Accomodation</span>
    				</div>
    				<div class="col-md-6">
    					<br>
    					<strong>College</strong><br><span class="data-college">NIT Calicut</span>
    				</div>
    			</div>
    		</div>
    	</div>   	

		<div id="registrations-container">

			@foreach($registrations as $registration)

			<div class="well registration-details" data-id="{{$registration->id}}">
				<form class="form-inline" role="form" method="POST">
    				<div class="form-group">
    					<input type="text" class="form-control" value="{{Config::get('app.id_prefix').$registration->id}}" readonly>
    				</div>
    				<div class="form-group">
    					<input type="text" class="form-control" name="name" placeholder="Name" value="{{$registration->name}}">
    				</div>
    				<div class="form-group">
    					<input type="text" class="form-control" name="email" placeholder="Email Address" value="{{$registration->email}}">
    				</div>
    				<div class="form-group">
    					<input type="text" class="form-control" name="phone" placeholder="Phone" value="{{$registration->phone}}">
    				</div>
    				<div class="form-group">
    					<select name="hospitality_type" class="form-control">
    						<option value="0" @if($registration->hospitality_type == 0) selected="selected" @endif>No Accomodation</option>
    						<option value="1" @if($registration->hospitality_type == 1) selected="selected" @endif>Yes, Male</option>
    						<option value="2" @if($registration->hospitality_type == 2) selected="selected" @endif>Yes, Female</option>
    					</select>
    				</div>
    				<br><br>
    				<div class="form-group college-container">
    					<input type="text" class="form-control college_name" value="{{$registration->college->name}}" style="width:500px;" readonly>
    					<button type="button" class="btn btn-default action-change-college"><span class="glyphicon glyphicon-pencil"></span> Change College</button>
    					<input type="hidden" name="college_id" value="{{$registration->college->id}}">
    				</div>
    			</form>
			</div>

			@endforeach
		</div>

		<button type="button" class="btn btn-success btn-lg" id="action-complete-registration"><span class="glyphicon glyphicon-plus"></span> Confirm Registrations</button>
		&nbsp;&nbsp;<img id="loading-animation" style="display:none;" src="{{URL::to('/')}}/css/loading.gif">		
		
		<br><br>

    </div>
    <div class="col-md-2">
    	<table class="table table-bordered table-hover">
    		<thead>
    			<tr>
    				<th>Type</th>
    				<th>Amount ₹</th>
    			</tr>
    		</thead>
    		<tbody>
    			<tr>
    				<td>Registration</td>
    				<td><span id="registration-total">0</span></td>
    			</tr>
    			<tr>
    				<td>Hospitality</td>
    				<td><span id="hospitality-total">0</span></td>
    			</tr>


    			<tr>
    				<td><strong>Total</strong></td>
    				<td><strong><span id="net-total">0</span></strong></td>
    			</tr>
    		</tbody>
    	</table>
    </div>
</div>

@stop


@section('scripts')

<script src="{{URL::to('/')}}/bower_components/select2/select2.min.js"></script>
<script>
$(function() {
	var value_reg = 200;
	var value_hosp = 250;

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


	$('#action-complete-registration').on('click', function(event) {
		event.preventDefault();

		$('.registration-details').each(function(index, el) {	
			var $this = $(this);
			setTimeout(function(){

				//Save the data via ajax, show success form, and clear existing one.
				var input_data = {
					type  : 'confirm',
					name  : $this.find('input[name="name"]').val(),
					email : $this.find('input[name="email"]').val(),
					phone : $this.find('input[name="phone"]').val(),
					hospitality_type : $this.find('select[name="hospitality_type"]').val(),
					college_id : $('.registration-details').find('input[name="college_id"]').val(),
					_token: _token
				};

				$('#loading-animation').show();



				//Save
				$.ajax({
				  url: '{{ URL::route('software_student_registration_save') }}',
				  type: 'POST',
				  dataType: 'json',
				  data: input_data,
				  success: function(data, textStatus, xhr) {
				  	$('#loading-animation').hide();

				  	if(data.result == 'success'){
				  		//Show success
				  		var clone = $('.registration-success').first().clone();
				  		clone.find('.data-name').html(data.name);
				  		clone.find('.data-email').html(data.email);
				  		clone.find('.data-phone').html(data.phone);
				  		clone.find('.data-college').html(data.college);
				  		clone.find('.data-hospitality').html(data.hospitality);
				  		clone.find('.data-id').html(data.id);
				  		clone.data('hospitality', data.hospitality_yn);
				  		clone.appendTo('#registration-success-container');
				  		clone.fadeIn(200);

				  		//Clear existing
				  		init_college();
				  		$('.registration-details').find('input[type="text"]').val('');
				  		$('.registration-details').find('select[name="hospitality_type"]').val(0);

				  		//Update Total
				  		var reg_total = ($('.registration-success').length - 1)*value_reg;
				  		$('#registration-total').html(reg_total);

				  		var $hosp = $('.registration-success').filter(function() { 
				  		  return $(this).data("hospitality") == 'yes'; 
				  		});

				  		var hosp_total = ($hosp.length)*value_hosp;
				  		$('#hospitality-total').html(hosp_total);

				  		$('#net-total').html(reg_total+hosp_total);
				  	}else{

				  	}
				  },
				  error: function(xhr, textStatus, errorThrown) {
				  	$('#loading-animation').hide();
				  	alert('An error occured. Make sure you are logged in. Refresh the page and try again.');
				  }

				});

				$this.fadeOut(300);
			}, index*600);
		});

	
		$('#action-complete-registration').remove();
	});


});
</script>
@stop