@extends('layouts.user')

@section('title')
New Registration
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">New Registration</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-md-10">
    	<div id="registration-success-container">
    		<div class="well registration-success" style="background:#E1EDC9; display:none; " data-hospitality="">
    			<div class="row">
    				<div class="col-md-3">
    					<strong>ID<br><h1><strong>{{Config::get('app.id_prefix')}}<span class="data-id"></span></strong></h1></strong>
    				</div>
    				<div class="col-md-3">
    					<strong>Name</strong><br><span class="data-name"></span>
    				</div>
    				<div class="col-md-3">
    					<strong>Email</strong><br><span class="data-email"></span>
    				</div>
    				<div class="col-md-3">
    					<strong>Phone</strong><br><span class="data-phone"></span>&nbsp;
    				</div>
    				<div class="col-md-3">
    					<br>
    					<strong>Hospitality</strong><br><span class="data-hospitality"></span>
    				</div>
    				<div class="col-md-6">
    					<br>
    					<strong>College</strong><br><span class="data-college"></span>
    				</div>
    			</div>
    		</div>
    	</div>   	

		<div id="registrations-container">
			<div class="well registration-details">
				<form class="form-inline" role="form" method="POST">
    				<div class="form-group">
    					<input type="text" class="form-control" name="name" placeholder="Name" autocomplete="off">
    				</div>
    				<div class="form-group">
    					<input type="text" class="form-control" name="email" placeholder="Email Address" autocomplete="off">
    				</div>
    				<div class="form-group">
    					<input type="text" class="form-control" name="phone" placeholder="Phone" autocomplete="off">
    				</div>
    				<div class="form-group">
    					<select name="hospitality_type" class="form-control">
    						<option value="0">No Accomodation</option>
    						<option value="1">Yes, Male</option>
    						<option value="2">Yes, Female</option>
    					</select>
    				</div>
    				<br><br>
    				<div class="form-group college-container">
    					<select name="college_id" class="college_select" style="width:500px;">
    					    <option value="0">Loading...</option>
    					</select> College
    				</div>
    			</form>
			</div>
		</div>

		<button type="button" class="btn btn-success" id="action-complete-registration"><span class="glyphicon glyphicon-plus"></span> Complete</button>
		<button type="button" class="btn btn-default" id="action-add-registration"><span class="glyphicon glyphicon-plus"></span> Save and Add New</button>
		&nbsp;&nbsp;<img id="loading-animation" style="display:none;" src="{{URL::to('/')}}/css/loading.gif">		
		
		<br><br>

		<div class="alert alert-danger" id="alert-errors" style="display:none;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<div id="errors">

			</div>
		</div>

		<br>

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

	var last = false;



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
	$('.college_select').select2({
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


	$('#action-add-registration').on('click', function(event) {
		event.preventDefault();

		//Save the data via ajax, show success form, and clear existing one.

		var input_data = {
			type  : 'new',
			name  : $('.registration-details').find('input[name="name"]').val(),
			email : $('.registration-details').find('input[name="email"]').val(),
			phone : $('.registration-details').find('input[name="phone"]').val(),
			hospitality_type : $('.registration-details').find('select[name="hospitality_type"]').val(),
			college_id : $('.registration-details').find('select[name="college_id"]').val(),
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
		  		clone.slideDown(200);

		  		//Clear existing
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


		  		$('#alert-errors').hide();
		  		$('#errors').html('');

		  		$('.registration-details').css({
		  			backgroundColor: '',
		  		});

		  		if(last == true){
		  			$('#action-complete-registration').remove();
		  			$('#action-add-registration').remove();
		  			$('.registration-details').last().remove();
		  		}

		  	}else{

		  		$('.registration-details').css({
		  			backgroundColor: '#f2dede',
		  		});

		  		$('#alert-errors').show();
		  		$('#errors').html(data.error_messages);

		  	}
		  },
		  error: function(xhr, textStatus, errorThrown) {
		  	$('#loading-animation').hide();

		  	alert('An error occured. Make sure you are logged in. Refresh the page and try again.');
		  }

		});
	});

	$('#action-complete-registration').on('click', function(event) {
		event.preventDefault();

		$('#action-add-registration').trigger('click');
		last = true;

	});

});
</script>
@stop