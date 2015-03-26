@extends('layouts.user')

@section('title')
Block Events
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Block Events</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-md-5">
                <form action="{{URL::route('software_block_events_do')}}" method="GET" role="form">        
                    <div class="form-group">
                        <select name="event_code" id="event_select" class="form-control" required="required">
                            <option>-- Select Event --</option>
                            @foreach($not_blocked as $event)
                                <option value="{{$event->event_code}}">{{$event->event_code}} : {{$event->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="to" value="0">
                    <button type="submit" class="btn btn-danger">Block Event <span class="glyphicon glyphicon-ban-circle"></span></button>
                </form>
                <br><br>
            </div>
        </div>

        <p>New registrations and confirmations are not allowed for blocked events.</p>

    	<table class="table table-striped table-hover">
    	    <thead>
    	        <tr>
    	            <th>#</th>
    	            <th>Event Name</th>
    	            <th>Event Code</th>
    	            <th>Actions</th>
    	        </tr>
    	    </thead>
    	    <tbody>
                <?php $i=0;?>
                @foreach($blocked_events as $event)                                
                <?php $i++;?>
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$event->name}}</td>
                    <td>{{$event->event_code}}</td>
                    <td><a href="{{URL::route('software_block_events_do')}}?to=1&event_code={{$event->event_code}}" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-trash"></span> Unblock</a></td>
                </tr>
                @endforeach
                @if($i==0)
                <tr>
                    <td colspan="4">
                    No blocked events.
                </tr>
                @endif
    	    </tbody>
    	</table>

    </div>
</div>

@stop

@section('scripts')
<script src="{{URL::to('/')}}/bower_components/select2/select2.min.js"></script>
<script>
  $(function() {

    $('#event_select').select2();
  });
</script>

@stop