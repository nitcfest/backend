@extends('layouts.user')

@section('title')
Event Registrations
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Event Registrations</h1>
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
                    <th>Event</th>
                    <th>Team Code</th>
                    <th>Owner ID</th>
                    <th>No. of Members</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($registrations as $registration)
                   <?php $i++; ?>
                   <tr>
                       <td>{{$i}}</td>
                       <td>{{$registration->event->name }}</td>
                       <td>{{$registration->event_code.$registration->team_code }}</td>
                       <td>{{Config::get('app.id_prefix').$registration->owner_id }}</td>
                       <td>{{count($registration->team_members) }}</td>
                       <td><a href="{{URL::route('manager_event_registration_details', $registration->id) }}" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-stats"></span> Team Details</a></td>
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