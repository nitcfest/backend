@extends('layouts.user')

@section('title')
Hospitality List
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Ragam '15 - Hospitality</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">

        <button id="print-btn" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-print"></span> Print</button>
        <br><br>
        
        @if($hospitality[0]->checkout == 1)
        <p>This team has checked out.</p><br>
        @endif

    	<table class="table table-striped table-hover" id="hospitality_table">
    	    <thead>
    	        <tr>
    	            <th>#</th>
    	            <th>Ragam ID</th>
    	            <th>Name</th>
    	            <th>Phone</th>
    	            <th>College</th>
                    <th>Location</th>
                    <th>Room</th>
                    <th>Bed</th>
    	        </tr>
    	    </thead>
    	    <tbody>
    	        <?php $i=0; ?>
    	        @foreach ($hospitality as $person)
                   <?php $registration = $person->registration; ?>
    	           <?php $i++; ?>
    	           <tr>
    	               <td>{{$i}}</td>
    	               <td>{{Config::get('app.id_prefix').$registration->id }}</td>
    	               <td>{{$registration->name }}</td>
    	               <td>{{$registration->phone }}</td>
    	               <td>@if($registration->college){{$registration->college->name }}@endif</td>
                       <td>{{$registration->hospitality->location}}</td>
                       <td>{{$registration->hospitality->room_no}}</td>
                       <td>{{$registration->hospitality->bed_no}}</td>
    	           </tr>
    	        @endforeach
    	    </tbody>
    	</table>       


        <br>
        @if($hospitality[0]->checkout == 0)
        <a href="{{URL::route('software_hospitality_checkout')}}?team_captain={{$team_captain}}" id="checkout-btn" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-plane"></span> Checkout Team</a>
        @endif
    </div>
</div>

@stop

@section('scripts')
<script>
    $(function() {
        $('#print-btn').on('click', function(event) {
            event.preventDefault();

            $('#print-btn, #checkout-btn').hide();

            print();



        });
    });
</script>
@stop