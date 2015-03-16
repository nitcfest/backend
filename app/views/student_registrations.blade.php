@extends('layouts.user')

@section('title')
Student Registrations
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Student Registrations</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">

        <div class="row">
          <div class="col-md-4 col-lg-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{count($registrations)}}</div>
                            <div class="big">Students Registered</div>
                        </div>
                    </div>
                </div>
            </div>
            @if($hide!=2)
            {{--  Change this 2 to the correct college id. --}}
            <a href="{{ URL::route('manager_student_registrations') }}?hide=2" class="btn btn-info btn-lg btn-block"><span class="fa fa-minus-circle"></span> Hide NIT Calicut Registrations</a>
            @else
            <a href="{{ URL::route('manager_student_registrations') }}" class="btn btn-info btn-lg btn-block"><span class="fa fa-plus-circle"></span> Show NIT Calicut Registrations</a>
            @endif
          </div>
        </div>
        <br>


        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>College</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($registrations as $registration)
                   <?php $i++; ?>
                   <tr>
                       <td>{{Config::get('app.id_prefix').$registration->id}}</td>
                       <td>{{$registration->name }}</td>
                       <td>{{$registration->email }}</td>
                       <td>{{$registration->phone }}</td>
                       <td>@if ($registration->college) {{$registration->college->name }} @endif</td>
                   </tr>
                @endforeach
            </tbody>
        </table>      


    </div>
</div>
<!-- /.row -->

@stop

@section('scripts')


@stop