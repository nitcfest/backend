@extends('layouts.user')

@section('title')
New Registrations
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">New Registrations</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">

    	<form class="form-inline" role="form" method="POST">

    		<div id="registrations-container">
    			<div class="well registration-details">
    				<div class="form-group">
    					<input type="text" class="form-control" placeholder="Name">
    				</div>
    				<div class="form-group">
    					<input type="text" class="form-control" placeholder="Email Address">
    				</div>
    				<div class="form-group">
    					<input type="text" class="form-control" placeholder="Phone">
    				</div>
    				<div class="form-group">
    					<label class="radio-inline">
    						<input type="radio" name="hospitality_type" value="0" checked="checked"> No Accomodation
    					</label>
    					<label class="radio-inline">
    						<input type="radio" name="hospitality_type" value="1"> Male
    					</label>
    					<label class="radio-inline">
    						<input type="radio" name="hospitality_type" value="2"> Female
    					</label>
    				</div>
    				<br><br>
    				<div class="form-group college-container">
    					<select class="college_select" style="width:500px;">
    					    <option value="0">Loading...</option>
    					</select>
    				</div>
    			</div>

    		</div>
    	</form>

		<button type="button" class="btn btn-default" id="action-add-registration"><span class="glyphicon glyphicon-plus"></span> Add More</button>
		
    	<br><br>
	    <button type="submit" class="btn btn-lg btn-info">Complete Registration <span class="glyphicon glyphicon-chevron-right"></span></button>

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

	var init_college = function(){
		//initialize the college select box
		$(".college_select").last().select2({
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




	$('#action-add-registration').on('click', function(event) {
		event.preventDefault();

		var clone = $('.registration-details').first().clone().hide();
		clone.find('.college-container').html('<select class="college_select" style="width:500px;"><option value="0">Loading...</option></select>');

		$('#registrations-container').append(clone);


		$('.registration-details').last().slideDown();

		init_college();

	});


});
</script>
@stop