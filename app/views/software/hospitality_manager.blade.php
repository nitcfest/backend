@extends('layouts.user')

@section('title')
Hospitality Manager
@stop

@section('head')
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

    	<table class="table table-striped table-hover">
    	    <thead>
    	        <tr>
    	            <th>#</th>
    	            <th>Ragam ID</th>
    	            <th>Reg. Status</th>
    	            <th>Name</th>
    	            <th>Email</th>
    	            <th>Phone</th>
    	            <th>College</th>
    	            <th>Hospitality Type</th>
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
    	               <td>{{$registration->email }}</td>
    	               <td>{{$registration->phone }}</td>
    	               <td>@if($registration->college){{$registration->college->name }}@endif</td>
    	               <td>@if($registration->hospitality_type == 1)Male @else Female @endif</td>
    	           </tr>
    	        @endforeach
    	    </tbody>
    	</table>


    </div>
</div>

@stop

@section('scripts')


@stop