@extends('layouts.user')

@section('title')
Pending Registrations
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Pending Registrations</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">

        <div class="row">
          <div class="col-md-4 col-lg-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{$pending_count}}</div>
                            <div class="big">Pending Registrations</div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>

        <a href="{{ URL::route('manager_verify_colleges') }}" class="btn btn-info btn-lg"><span class="fa fa-university"></span> Verify Colleges</a>
        <br>
        <br>
        <p>The rows marked in green have completed the registration process after requesting a new college.</p>


        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>College</th>
                    <th>College Status</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($registrations as $registration)
                   <?php $i++; ?>
                   

                   @if($registration->status == 'Registered') <tr class="success"> @else <tr> @endif


                       <td>{{ $i }}</td>
                       <td>{{$registration->name }}</td>
                       <td>{{$registration->email }}</td>
                       <td>{{$registration->phone }}</td>
                       <td>@if ($registration->college) {{$registration->college->name }} @endif</td>
                       <td>@if ($registration->college) {{$registration->college_status }} @endif</td>
                       <td>{{ $registration->status }}</td>
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