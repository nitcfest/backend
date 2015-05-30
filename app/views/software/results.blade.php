@extends('layouts.user')
    
@section('title')
Results
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/select2/select2.min.css" rel="stylesheet">
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Results</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-md-5">
                <form action="#" method="GET" role="form">        
                    <div class="form-group">
                        <select name="event_code" id="event_select" class="form-control" required="required">
                            <option>-- Select Event --</option>
                            @foreach($events as $event)
                                <option value="{{$event->event_code}}">{{$event->event_code}} : {{$event->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="to" value="0">
                    <button type="submit" class="btn btn-info">Show/Add Results <span class="glyphicon glyphicon-ban-circle"></span></button>
                </form>
                <br><br>
            </div>
        </div>



        <form action="" method="GET" role="form">

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width:15%;">Position</th>
                        <th style="width:40%;">College</th>
                        <th style="width:15%;">Team ID</th>
                        <th style="width:30%;">Team Members</th>
                    </tr>
                </thead>
                <tbody id="results-data">
                    <tr>
                        <td><input type="text" class="form-control" name="position[]"></td>
                        <td><input type="text" class="form-control" name="college[]"></td>
                        <td>
                            <input type="text" class="form-control" name="team_id[]">
                        </td>
                        <td>
                            <textarea class="form-control" rows="3" name="team_members[]"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>

           
            
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Save Results</button>
            <button type="button" class="btn btn-default" id="add-position"><span class="glyphicon glyphicon-plus"></span> Add Position</button>
        </form>



    </div>
</div>

@stop

@section('scripts')
<script src="{{URL::to('/')}}/bower_components/select2/select2.min.js"></script>
<script>
  $(function() {

    $('#event_select').select2();

    $('#add-position').on('click', function(event) {
        event.preventDefault();

        clone = $('#results-data').find('tr').first().clone();
        clone.find('input[type="text"]').val('');
        clone.find('textarea').val('');

        clone.appendTo('#results-data')
    });

  });
</script>

@stop