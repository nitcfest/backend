@extends('layouts.user')

@section('title')
Edit Event
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit Event</h1>
        <p>
    </div>
</div>

<form action="" method="POST" role="form">
    <div class="row">
        <div class="col-md-6">
            <h3>Basic Details</h3>
            <div class="form-group">
                <label>Event Code</label>
                <input type="text" class="form-control" name="event_code">
            </div>
            <div class="form-group">
                <label>Event Name</label>
                <input type="text" class="form-control" name="name">
            </div>
            <div class="form-group">
                <label>Short Description</label>
                <textarea class="form-control" rows="2" name="short_description"></textarea>
            </div>
            <div class="form-group">
                <label>Tags (seperate by comma, used for search)</label>
                <textarea class="form-control" rows="2" name="tags"></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <h3>Contacts</h3>
        </div>
    </div>
</form>

@stop

@section('scripts')

@stop