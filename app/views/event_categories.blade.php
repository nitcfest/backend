@extends('layouts.user')

@section('title')
Event Categories
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Event Categories</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-4 col-md-6">
        <button type="button" class="btn btn-lg btn-info" id="btn-add-category"><span class="glyphicon glyphicon-plus"></span> Add Event Category</button>
        <div class="well" id="div-add-category" style="display:none;">
            <form action="{{URL::route('action_add_event_category')}}" method="POST" role="form">
                {{Form::token()}}
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Category Name" required>
                </div>
                <div class="form-group">
                    <label>Parent Category</label>
                    <select name="parent_id" class="form-control">
                        <option value="0">Root/Parent</option>
                        @foreach ($event_categories as $category)
                            @if ($category->parent_id == 0)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>    
                            @endif
                        @endforeach
                    </select>
                </div>           
            
                <button type="submit" class="btn btn-success">Add Category <span class="glyphicon glyphicon-chevron-right"></span></button>
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
                    <th>Type</th>
                    <th>Number of Events</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($event_categories as $category)
                   <?php $i++; ?>
                   <tr>
                       <td>{{$i}}</td>
                       <td>{{$category->name }}</td>
                       <td>{{$category->type }}</td>
                       <td>{{$category->events }}</td>
                       <td>
                            @if ($category->events == 0)
                                <a href="{{ URL::route('action_delete_event_category', $category->id) }}" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</a>
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
        $('#btn-add-category').on('click', function(event) {
            event.preventDefault();

            if($('#div-add-category').is(':hidden'))
                $('#div-add-category').slideDown();
            else
                $('#div-add-category').slideUp();
        });
    });

</script>
@stop