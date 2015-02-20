@extends('layouts.user')

@section('title')

@if($page_type=='new')
    New Event
@else
    Edit Event
@endif

@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/bootstrap3-wysiwyg/bootstrap3-wysihtml5.min.css" rel="stylesheet">
<link href="{{URL::to('/')}}/bower_components/bootstrap3-wysiwyg/wysihtml5-image-upload.css" rel="stylesheet">
<link href="{{URL::to('/')}}/bower_components/blueimp-jquery-file-upload/jquery.fileupload.css" rel="stylesheet">


@stop

@section('content')

<form action="{{ URL::route('action_save_event') }}" id="main_form" method="POST" role="form">
    
    @if($page_type=='new')
        <input type="hidden" name="current_id" value="new">
    @else
        <input type="hidden" name="current_id" value="{{$id}}">
    @endif

    {{Form::token()}}

    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">
                @if($page_type=='new')
                    New Event
                @else
                    Edit Event
                @endif

            </h1>
            <button type="submit" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Save Changes</button>
        </div>
        <div class="col-lg-6">
        <br>
            @if (Session::get('error'))
                <div class="alert alert-error alert-danger">{{ Session::get('error') }}</div>
            @endif

            @if (Session::get('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <h3>Basic Details</h3>
            <div class="form-group">
                <label>Event Code (3 characters)</label>
                <input type="text" class="form-control" name="event_code" id="event_code" value="{{ $event->event_code }}" required>
            </div>
            <div class="form-group">
                <label>Event Name</label>
                <input type="text" class="form-control" name="name" value="{{ $event->name }}" required>
            </div>
            <div class="form-group">
                <label>Event Category</label>

                <select name="category_id" class="form-control">
                    @foreach ($event_categories as $category)
                            <option value="{{ $category->id }}" @if($category->id == $event->category_id) selected="selected" @endif>@if($category->parent_id === 0) Root :: @endif {{ $category->name }}</option>
                    @endforeach
                </select>


            </div>
            <div class="form-group">
                <label>Short Description</label>
                <textarea class="form-control" rows="2" name="short_description" required>{{ $event->short_description }}</textarea>
            </div>
            <div class="form-group">
                <label>Tags (seperate by comma, used for search)</label>
                <textarea class="form-control" rows="3" name="tags">{{ $event->tags }}</textarea>
            </div>

            <div class="form-group">
                <label>Min. Team Size (1 for individual)</label>
                <input type="text" class="form-control" name="team_min" value="{{ $event->team_min }}" required>
            </div>

            <div class="form-group">
                <label>Max. Team Size (1 for individual)</label>
                <input type="text" class="form-control" name="team_max" value="{{ $event->team_max }}" required>
            </div>

            <div class="form-group">
                <label>Prizes</label>
                <textarea class="form-control" rows="4" name="prizes">{{ $event->prizes }}</textarea>
            </div>

            <div class="form-group">
                <label>Event Email (if available)</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="event_email" value="{{$event->event_email}}">
                    <div class="input-group-addon">{{'@'.Config::get('app.domain')}}</div>
                </div>
            </div>

            <h3>Contacts</h3>

            <?php $i=1 ?>
            @foreach($data->contacts as $contact)
            <div class="well">
                <h4>Manager {{$i}} @if($i!=1) (optional) @endif</h4>  
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="manager_name[]" value="{{$contact['name']}}" @if($i==1) required @endif >
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" name="manager_phone[]" value="{{$contact['phone']}}" @if($i==1) required @endif >
                </div>
                <div class="form-group">
                    <label>Email (optional)</label>
                    <input type="text" class="form-control" name="manager_email[]" value="{{$contact['email']}}" @if($i==1) @endif >
                </div>
                <div class="form-group">
                    <label>Facebook Link (optional)</label>
                    <input type="text" class="form-control" name="manager_facebook[]" value="{{$contact['facebook']}}" @if($i==1) @endif >
                </div>
            </div>

            <?php $i++; ?>
            @endforeach

           
        </div>
        <div class="col-md-8">
            <h3>Event Description</h3><br>
            <div id="section-block-container">

                @foreach($data->sections as $section)
                <div class="section-block">
                    <div class="section-editor well">
                        <div class="form-group">
                            <label>Section Title</label>
                            <input type="text" class="form-control section-title" name="section_title[]" value="{{ $section['title'] }}" required>
                        </div>  
                        <div class="form-group">
                            <label>Description</label>
                            <div class="editor-container">              
                                <textarea class="section-editor-textarea" placeholder="" style="width: 100%; height: 400px;" name="section_description[]">{{$section['text']}}</textarea>
                            </div>
                        </div>  

                        <!-- THIS IS A HIDDEN FEATURE :P
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-default btn-section-move-up">Move Up <span class="glyphicon glyphicon-chevron-up"></span></button>
                            <button type="button" class="btn btn-default btn-section-move-down"><span class="glyphicon glyphicon-chevron-down"></span> Move Down</button>
                        </div>
                        <div class="clearfix"></div> -->
                    </div>
                </div>
                @endforeach

            </div>

            <button type="button" class="btn btn-info btn-lg" id="btn-add-section"><span class="glyphicon glyphicon-plus"></span> Add Section</button>
            <button type="button" class="btn btn-default btn-lg" id="btn-remove-section"><span class="glyphicon glyphicon-minus"></span> Delete Previous</button>
        </div>
    </div>
</form>

<div style="height:50px;"></div>

@stop

@section('scripts')
<script src="{{URL::to('/')}}/bower_components/bootstrap3-wysiwyg/bootstrap3-wysihtml5.all.min.js"></script>

<script src="{{URL::to('/')}}/bower_components/blueimp-jquery-file-upload/jquery.ui.widget.js"></script>
<script src="{{URL::to('/')}}/bower_components/blueimp-jquery-file-upload/jquery.iframe-transport.js"></script>
<script src="{{URL::to('/')}}/bower_components/blueimp-jquery-file-upload/jquery.fileupload.js"></script>

<script src="{{URL::to('/')}}/bower_components/bootstrap3-wysiwyg/wysihtml5-image-upload.js"></script>


<script>
    $(function() {

        @if(Auth::manager()->get()->role==2)
            $('#event_code').attr('readonly',true);
        @endif


        $.fn.scrollTo = function (speed) {
            if (typeof(speed) == 'undefined')
                speed = 1000;

            $('html, body').animate({
                scrollTop: parseInt($(this).offset().top)
            }, speed);
        };

        var editor_settings = {
                "useLineBreaks": true,
                "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": true, //Italics, bold, etc. Default true
                "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                "html": true, //Button which allows you to edit the generated HTML. Default false
                "link": false, //Button to insert a link. Default true
                "image": true, //Button to insert an image. Default true,
                "color": false, //Button to change color of font  
                "blockquote": false, //Blockquote  
                "instance": 1,
                "events": {
                        "load": function() { 
                            //Hide intend and deintend options.
                            $('.wysihtml5-toolbar').find('.glyphicon-indent-right, .glyphicon-indent-left').parents('a').hide();
                        },
                },
            };

        $('.section-block').each(function(index){
            $(this).find('.section-editor-textarea').wysihtml5(editor_settings);
            editor_settings.instance+=1;
        });

        $('.section-block').first().find('.section-title').attr('readonly',true);

        $('#btn-add-section').on('click', function(event) {
            event.preventDefault();

            var block = $('.section-block').last().clone();
            block.find('.section-title').val('').attr('readonly',false);
            block.find('.editor-container').html('<textarea class="section-editor-textarea" placeholder="" style="width: 100%; height: 400px;" name="section_description[]"></textarea>');
            block.appendTo('#section-block-container');
            editor_settings.instance+=1;

            $('.section-block').last().find('.section-editor-textarea').wysihtml5(editor_settings);
            $('.section-block').last().scrollTo();
        });

        $('#btn-remove-section').on('click', function(event) {
            event.preventDefault();

            if($('.section-block').length > 1){
                $('.section-block').last().remove();
            }
        });

        $('#main_form').submit(function(event) {
            $('.section-block').each(function(){
                if($(this).find('.section-editor-textarea').val() == ''){
                    alert('A section cannot be left blank. Remove the section if it is not required.');
                    event.preventDefault();
                    return false;
                }
            });

            return true;                                            
        });

        // Move functions. Not working perfect right now.
        // $('#section-block-container').on('click', '.btn-section-move-up', function(event) {
        //     event.preventDefault();

        //     var current_parent = $(this).parents('.section-block');

        //     var position = $('.section-block').index(current_parent);

        //     if(position > 0){
        //         $('.section-block').eq(position-1).insertAfter($('.section-block').eq(position));               
        //     }
        // });

        // $('#section-block-container').on('click', '.btn-section-move-down', function(event) {
        //     event.preventDefault();

        //     var current_parent = $(this).parents('.section-block');

        //     var position = $('.section-block').index(current_parent);
        //     var last = $('.section-block').index($('.section-block').last());

        //     if(position < last){
        //         $('.section-block').eq(position+1).insertBefore($('.section-block').eq(position));
        //     }
        // });





    });
</script>
@stop