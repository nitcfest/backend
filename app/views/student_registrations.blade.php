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
                       <td>{{$registration->college->name }}</td>
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