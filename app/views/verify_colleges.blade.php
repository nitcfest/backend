@extends('layouts.user')

@section('title')
Verify Colleges
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Verify Colleges</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="row">

            <div class="col-lg-6">
                <strong>Before validating, check if a college already exists by searching here:</strong>
                <select name="college" id="college_select" style="width:100%;">
                    <option value="0">Loading...</option>
                </select>

                <br><br><br>

                <p>Make sure you validate only the colleges that are real. Allow only full college names, not shortened ones. 
                For example, do not validate "NIT Calicut", instead "National Institute of Technology Calicut" is preferred.</p>
            </div>


            <div class="col-lg-6">
                @if (Session::get('error'))
                    <div class="alert alert-error alert-danger">{{ Session::get('error') }}</div>
                @endif

                @if (Session::get('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
            </div>
        </div>


        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Created at</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($colleges as $college)
                   <?php $i++; ?>
                   <tr>
                       <td>{{$i}}</td>
                       <td>{{$college->created_at}}</td>
                       <td>{{$college->name }}</td>
                       <td>Not Validated</td>
                       <td>
                        <a href="{{URL::route('action_update_college_status')}}?id={{$college->id}}&to=validate" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-ok"></span> Validate</a>
                        <a href="{{URL::route('action_update_college_status')}}?id={{$college->id}}&to=block" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-ban-circle"></span> Block</a>
                       </td>
                   </tr>
                @endforeach
            </tbody>
        </table>      


    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-8">
    </div>
    <div class="col-lg-4">
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
            if(college.status == 'Blocked')
                var markup =  '<div>(Blocked) '+college.name+'</div>';
            else
                var markup =  '<div>'+college.name+'</div>';

            return markup;
          }

          function formatCollegeSelection (college) {
            return '<div style="height:28px;">'+ (college.name || '<span style="color:#787878">Search for a college...</span>') +'</div>';
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
                        page: params.page,
                        show_admin: true,
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




    });

</script>

@stop