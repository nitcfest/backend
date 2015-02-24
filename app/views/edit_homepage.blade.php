@extends('layouts.user')

@section('title')
Edit Homepage
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/bootstrap3-wysiwyg/bootstrap3-wysihtml5.min.css" rel="stylesheet">
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit Homepage</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <h3>Updates/News</h3>

        <div class="row">
            <div class="col-lg-6">
                <button type="button" class="btn btn-lg btn-info" id="btn-add-update"><span class="glyphicon glyphicon-plus"></span> Add Update</button>
                <div class="well" id="div-add-update" style="display:none;">
                    <form action="{{URL::route('action_homepage_add_update')}}" method="POST" role="form">
                        {{Form::token()}}
                        
                        <div class="form-group">
                            <label>Details (Use links if required)</label>
                            <textarea class="update-text" placeholder="" style="width: 100%; height: 200px;" name="text"></textarea>
                        </div>    
                    
                        <button type="submit" class="btn btn-success">Confirm Update <span class="glyphicon glyphicon-chevron-right"></span></button>
                    </form>
                </div>

            </div>
            <div class="col-lg-6">
                @if (Session::get('error'))
                    <div class="alert alert-error alert-danger">{{ Session::get('error') }}</div>
                @endif

                @if (Session::get('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
            </div>
        </div>


        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Created at</th>
                    <th>Text</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($updates as $update)
                   <?php $i++; ?>
                   <tr>
                       <td>{{$i}}</td>
                       <td>{{$update->created_at }}</td>
                       <td>{{$update->text }}</td>
                       <td>{{$update->status }}</td>
                       <td>
                        @if($update->displayed == false)
                        <a href="{{URL::route('action_update_display_status')}}?id={{$update->id}}&to=show" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-ok"></span> Show</a>
                        @else
                        <a href="{{URL::route('action_update_display_status')}}?id={{$update->id}}&to=hide" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span> Hide</a>
                        @endif
                       </td>
                       <td>
                        <a href="{{URL::route('action_update_delete')}}?id={{$update->id}}" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                       </td>
                   </tr>
                @endforeach
            </tbody>
        </table>      


    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-8">
    </div>
    <div class="col-lg-4">
    </div>
</div>

@stop

@section('scripts')

<script src="{{URL::to('/')}}/bower_components/bootstrap3-wysiwyg/bootstrap3-wysihtml5.all.min.js"></script>
<script>
    $(function() {
        $('#btn-add-update').on('click', function(event) {
            event.preventDefault();

            if($('#div-add-update').is(':hidden'))
                $('#div-add-update').slideDown();
            else
                $('#div-add-update').slideUp();
        });

        var editor_settings = {
                "useLineBreaks": true,
                toolbar : { 
                    "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
                    "emphasis": true, //Italics, bold, etc. Default true
                    "lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                    "html": true, //Button which allows you to edit the generated HTML. Default false
                    "link": true, //Button to insert a link. Default true
                    "image": false, //Button to insert an image. Default true,
                    "color": false, //Button to change color of font  
                    "blockquote": false, //Blockquote  
                },
                "events": {
                        "load": function() { 
                            //Hide intend and deintend options.
                            $('.wysihtml5-toolbar').find('.glyphicon-indent-right, .glyphicon-indent-left').parents('a').hide();
                        },
                },
            };

        $('.update-text').wysihtml5(editor_settings);


    });

</script>

@stop