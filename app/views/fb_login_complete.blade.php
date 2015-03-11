@extends('layouts.guest')

@section('title')
Complete your registration
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop

@section('content')

<div style="height:50px;"></div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Please complete your registration.</h3>
            </div>
            <div class="panel-body">
                <form role="form" method="POST" action="{{{ URL::route('api_fb_complete_post') }}}" accept-charset="UTF-8">
                	<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="Name" name="name" value="{{{ $name }}}" readonly>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Email" name="email" value="{{{ $email }}}" readonly>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Phone Number" name="phone" type="text" required>
                        </div>

                        <div class="form-group">
                            <select name="college" id="college_select" style="width:100%;">
                                <option value="0">Loading...</option>
                            </select>
                        </div>

                        Don't see your college? <a href="#" id="action-show-add-college">Add it here.</a><br>
                        <div class="well" id="div-add-college" style="display:none;">
                            <p>Before adding your college, make sure that it doesn't exist in the list. Only full and expanded names will be approved.
                            For example, <em>National Institute of Technology, Calicut</em> is preferred over <em>NIT Calicut</em>.</p>

                            <p><strong>You'll be able to register only after your college is verified. We regret any inconvenience.</strong></p>
                            
                            <div class="form-group">
                                <input type="text" class="form-control" id="name_new_college" placeholder="Full name of your college">
                            </div>

                            <button type="button" id="action-add-college" class="btn btn-default btn-block"><span class="glyphicon glyphicon-plus"></span> Add College</button>

                            <span id="add-college-messages"></span>

                        </div>            

                        <br>

                        <p> Do you need hospitality services during {{Config::get('app.main_name')}}?</p>
                        <div class="radio">
                            <label>
                                <input type="radio" name="hospitality_type" value="0" checked="checked">
                                No, I do not require accomodation.
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="hospitality_type" value="1">
                                Yes, I would like accomodation.
                            </label>
                        </div>
                        <br>

                        <input type="hidden" name="fb_uid" value="{{ $fb_uid }}">

                        <button type="submit" class="btn btn-lg btn-info btn-block">Complete Registration <span class="glyphicon glyphicon-chevron-right"></span></button>
                    </fieldset>
                </form>

                @if (Session::get('errors'))
                    <br>
                    <div class="alert alert-error alert-danger">{{ Session::get('errors') }}</div>
                @endif

            </div>
        </div>
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
        $("#college_select").select2({
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



        $("#action-show-add-college").on('click', function(event) {
            event.preventDefault();
            $('#div-add-college').slideDown();
        });


        $('#action-add-college').on('click', function(event) {
            event.preventDefault();

            var college_name = $('#name_new_college').val();

            $.ajax({
              url: '{{URL::route('api_new_college')}}',
              type: 'GET',
              dataType: 'jsonp',
              data: { college_name: college_name },
              success: function(data, textStatus, xhr) {
                if(data.result == 'success'){
                    $('#add-college-messages').html('<br>Your college has been added and is pending verification. Please try registering after a few hours.');
                }else if(data.reason == 'invalid_name'){
                    $('#add-college-messages').html('<br>The college name you entered is invalid. Please enter a valid name.');
                }else if(data.reason == 'name_exists'){
                    $('#add-college-messages').html('<br>The college name you entered already exists or is too generic. Try entering the expanded college name. If this name had just been added, it might be pending verification.');
                }
              },
              error: function(xhr, textStatus, errorThrown) {
                console.log('Error adding college. Try again.');
              }
            });
        });




    });

</script>

@stop