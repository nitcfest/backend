@extends('layouts.user')

@section('title')
Events
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Events</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-4 col-md-6">
        <a href="{{URL::route('action_new_event')}}" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-plus"></span> Add New Event</a>
    </div>
    <div class="col-lg-6 col-md-6">
        @if (Session::get('error'))
            <div class="alert alert-error alert-danger">{{ Session::get('error') }}</div>
        @endif

        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event Code</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($events as $event)
                   <?php $i++; ?>
                   <tr>
                       <td>{{$i}}</td>
                       <td>{{$event->event_code }}</td>
                       <td>{{$event->category->name }}</td>
                       <td>{{$event->name }}</td>
                       <td>{{$event->status }}</td>
                       <td><a href="{{URL::route('action_edit_event', $event->id)}}" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                   </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@section('scripts')

<script>
    $('#btn-add-manager').on('click', function(event) {
        event.preventDefault();

        if($('#div-add-manager').is(':hidden'))
            $('#div-add-manager').slideDown();
        else
            $('#div-add-manager').slideUp();
    });

    $('#form-select-role').on('change', function(event) {
        if($('#form-select-role').val() == 2)
            $('#div-event-code').slideDown();
        else
            $('#div-event-code').slideUp();
    });
</script>
@stop