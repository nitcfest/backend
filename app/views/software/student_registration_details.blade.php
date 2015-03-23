@extends('layouts.user')

@section('title')
Student Details
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Student Details</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped">
          <tbody>
            <tr>
              <td>ID</td>
              <td>{{Config::get('app.id_prefix').$registration->id}}</td>
            </tr>
            <tr>
              <td>Name</td>
              <td>{{$registration->name}}</td>
            </tr>
            <tr>
              <td>Email</td>
              <td>{{$registration->email}}</td>
            </tr>
            <tr>
              <td>Phone</td>
              <td>{{$registration->phone}}</td>
            </tr>
            <tr>
              <td>College</td>
              <td>{{$registration->college->name}}</td>
            </tr>
            <tr>
              <td>Hospitality Type</td>
              <td>@if($registration->hospitality_type==0) Not Required @elseif($registration->hospitality_type==1) Required (Male) @elseif($registration->hospitality_type==2) Required (Female) @endif </td>
            </tr>
            <tr>
              <td>Registration Status</td>
              <td>@if($registration->registration_confirm==0) Not Confirmed @else Confirmed @endif</td>
            </tr>
            <tr>
              <td>Hospitality Status</td>
              <td>@if($registration->hospitality_confirm==0) Not Confirmed @else Confirmed @endif</td>
            </tr>
          </tbody>
        </table>


        <h3>Event Registrations</h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event Name</th>
                    <th>Event Code</th>
                    <th>Team ID</th>
                    <th>Registration Status</th>
                </tr>
            </thead>
            <tbody>
            <?php $i=0; ?>
            @foreach ($teams as $team)
               <?php $i++; ?>
               <tr>
                   <td>{{$i}}</td>
                   <td>{{$team->team->event->name}}</td>
                   <td>{{$team->team->event->event_code}}</td>
                   <td>{{$team->team->event->event_code}}{{$team->team->team_code}}</td>
                   <td>@if($team->team->confirmation == 1) Confirmed @else Not Confirmed @endif </td>
               </tr>
            @endforeach

            @if($i==0)
              <tr>
                <td colspan="5">Not registered for any event.</td>
              </tr>
            @endif
            </tbody>
        </table>      


    </div>
</div>
<!-- /.row -->

@stop

@section('scripts')


@stop