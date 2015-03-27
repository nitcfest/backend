@extends('layouts.user')

@section('title')
Hospitality Manager
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/datatables/css/dataTables.bootstrap.css" rel="stylesheet">
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">

<style>
.dataTables_filter input { width: 400px !important }

</style>
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Hospitality Manager</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">

        <a href="{{URL::route('software_hospitality_allocate')}}" class="btn btn-lg btn-info">Allocate Rooms <span class="glyphicon glyphicon-chevron-right"></span></a>
        <br><br>
    	<table class="table table-striped table-hover" id="hospitality_table" style="display:none;">
    	    <thead>
    	        <tr>
    	            <th>#</th>
    	            <th>Ragam ID</th>
    	            <th>Reg. Status</th>
    	            <th>Name</th>
    	            <th>Phone</th>
    	            <th>College</th>
    	            <th>Hospitality Type</th>
    	            <th>Team Captain</th>
                    <th>Room</th>
                    <th>Bed</th>
                    <th>Checkout</th>
    	        </tr>
    	    </thead>
    	    <tbody>
    	        <?php $i=0; ?>
    	        @foreach ($registrations as $registration)
    	           <?php $i++; ?>
    	           <tr>
    	               <td>{{$i}}</td>
    	               <td>{{Config::get('app.id_prefix').$registration->id }}</td>
    	               <td>@if($registration->registration_confirm == 1) Confirmed @else Not Confirmed @endif</td> <td>{{$registration->name }}</td>
    	               <td>{{$registration->phone }}</td>
    	               <td>@if($registration->college){{$registration->college->name }}@endif</td>
    	               <td>@if($registration->hospitality_type == 1)Male @else Female @endif</td>

                       <td>@if($registration->hospitality) {{Config::get('app.id_prefix').$registration->hospitality->captain_id}} @endif</td>
                       <td>@if($registration->hospitality) {{$registration->hospitality->location}} {{$registration->hospitality->room_no}} @endif</td>
                       <td>@if($registration->hospitality) {{$registration->hospitality->bed_no}} @endif</td>
    	               <td>@if($registration->hospitality) @if($registration->hospitality->checkout == 0) No @else Yes @endif @endif</td>
                   </tr>
    	        @endforeach
    	    </tbody>
    	</table>

        <br>
        <h2>View Team</h2>

        <div class="row">
            <div class="col-md-6">
                <form action="{{URL::route('software_hospitality_show_team')}}" method="POST" role="form">        
                    <div class="form-group">
                        <select name="team_captain" id="team_select" class="form-control" required="required">
                            <option>-- Select Team Captain --</option>
                            @foreach($team_captains as $team_captain)
                                <option value="{{$team_captain->registration->id}}">{{Config::get('app.id_prefix').$team_captain->registration->id}} : {{$team_captain->registration->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info btn-lg">Show Team<span class="glyphicon glyphicon-chevron-right"></span></button>
                </form>

                <br>
                @if (Session::get('error'))
                    <div class="alert alert-error alert-danger">{{ Session::get('error') }}</div>
                @endif

                @if (Session::get('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif

            </div>
        </div>        

    </div>
</div>

@stop

@section('scripts')
<script src="{{URL::to('/')}}/bower_components/datatables/js/jquery.dataTables.min.js"></script>
<script src="{{URL::to('/')}}/bower_components/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="{{URL::to('/')}}/bower_components/select2/select2.min.js"></script>
<script>
    $(function() {
        var table = $('#hospitality_table').DataTable();
        $('#hospitality_table').show();

        $('#team_select').select2();

    });
</script>


@stop