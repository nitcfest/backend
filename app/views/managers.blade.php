@extends('layouts.user')

@section('title')
Managers
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Managers</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-4 col-md-6">
        <button type="button" class="btn btn-lg btn-info" id="btn-add-manager"><span class="glyphicon glyphicon-plus"></span> Add Manager</button>
        <div class="well" id="div-add-manager" style="display:none;">
            <form action="{{URL::route('action_new_manager')}}" method="POST" role="form">
                {{Form::token()}}
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Full name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Email address" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="text" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="role" id="form-select-role" class="form-control">
                        <option value="2">Event Manager</option>
                        <option value="8">Proofreader</option>
                        <option value="1">Website Admin</option>
                        <option value="3">Online Registration</option>

                        <option value="9">Registration - Basic</option>
                        <option value="10">Registration - Level 1</option>
                        <option value="11">Registration - Level 2</option>
                        <option value="12">Hospitality Manager</option>

                        <option value="5">Program Committee</option>
                        <option value="4">Hospitality - Online</option>
                        <option value="6">Results Entry</option>
                        <option value="7">Printing Only</option>
                        <option value="21">Super Admin</option>
                    </select>
                </div>
                <div class="form-group" id="div-event-code">
                    <label>Event Code</label>
                    <input type="text" class="form-control" name="event_code" placeholder="Event Code">
                </div>
            
            
                <button type="submit" class="btn btn-success">Create Account <span class="glyphicon glyphicon-chevron-right"></span></button>
            </form>
        </div>

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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Roll No</th>
                    <th>Event Code</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($managers as $manager)
                   <?php $i++; ?>
                   <tr>
                       <td>{{$i}}</td>
                       <td>{{$manager->name }}</td>
                       <td>{{$manager->email }}</td>
                       <td>{{$manager->type }}</td>
                       <td>{{$manager->roll_no }}</td>
                       <td>{{$manager->event_code }}</td>
                       <td>{{$manager->status }}</td>
                       <td>                           
                            @if($manager->validated == false)
                            <a href="{{URL::route('action_change_manager_status')}}?id={{$manager->id}}&to=validate" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-ok"></span> Validate</a>
                            @else
                            <a href="{{URL::route('action_change_manager_status')}}?id={{$manager->id}}&to=invalidate" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span> Invalidate</a>
                            @endif
                       </td>
                   </tr>
                @endforeach


            </tbody>
        </table>
    </div>
</div>

@stop

@section('scripts')
<script>
    $(function() {
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
    });

</script>
@stop