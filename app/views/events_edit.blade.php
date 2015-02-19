@extends('layouts.user')

@section('title')
Edit Event
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/bootstrap3-wysiwyg/bootstrap3-wysihtml5.min.css" rel="stylesheet">
<link href="{{URL::to('/')}}/bower_components/bootstrap3-wysiwyg/wysihtml5-image-upload.css" rel="stylesheet">
<link href="{{URL::to('/')}}/bower_components/blueimp-jquery-file-upload/jquery.fileupload.css" rel="stylesheet">


@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit Event</h1>
        <button type="button" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Save Changes</button>
    </div>
</div>

<form action="" method="POST" role="form">
    <div class="row">
        <div class="col-md-4">
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
                <textarea class="form-control" rows="3" name="tags"></textarea>
            </div>

            <div class="form-group">
                <label>Min. Team Size (1 for individual)</label>
                <input type="text" class="form-control" name="team_min">
            </div>

            <div class="form-group">
                <label>Max. Team Size (1 for individual)</label>
                <input type="text" class="form-control" name="team_min">
            </div>

            <div class="form-group">
                <label>Prizes</label>
                <textarea class="form-control" rows="3" name="prizes"></textarea>
            </div>

            <div class="form-group">
                <label>Event Email (if available)</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="event_email">
                    <div class="input-group-addon">{{'@'.Config::get('app.domain')}}</div>
                </div>
            </div>

            <h3>Contacts</h3>

            <div class="well">
                <h4>Manager 1</h4>  
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="manager1_name">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" name="manager1_phone">
                </div>
            </div>
            <div class="well">
                <h4>Manager 2 (optional)</h4>  
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="manager2_name">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" name="manager2_phone">
                </div>
            </div>
            <div class="well">
                <h4>Manager 3 (optional)</h4>  
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="manager3_name">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" name="manager3_phone">
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <h3>Event Description</h3><br>
            <div id="section-block-container">
                <div class="section-block">
                    <div class="section-editor well">
                        <div class="form-group">
                            <label>Section Title</label>
                            <input type="text" class="form-control section-title" name="section_title">
                        </div>  
                        <div class="form-group">
                            <label>Description</label>
                            <div class="editor-container">              
                                <textarea class="section-editor-textarea" placeholder="" style="width: 100%; height: 400px;"></textarea>
                            </div>
                        </div>  

                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-default">Move Up <span class="glyphicon glyphicon-chevron-up"></span></button>
                            <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-chevron-down"></span> Move Down</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
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

                "events": {
                        "load": function() { 
                            $('.wysihtml5-toolbar').find('.glyphicon-indent-right, .glyphicon-indent-left').parents('a').hide();
                        },
                },
            };

        $('.section-editor-textarea').wysihtml5(editor_settings);
        

        $('#btn-add-section').on('click', function(event) {
            event.preventDefault();

            var block = $('.section-block').last().clone();
            block.find('.section-title').val('');
            block.find('.editor-container').html('<textarea class="section-editor-textarea" placeholder="" style="width: 100%; height: 400px;"></textarea>');
            block.appendTo('#section-block-container');
            $('.section-block').last().find('.section-editor-textarea').wysihtml5(editor_settings);

            $('.section-block').last().scrollTo();
        });

        $('#btn-remove-section').on('click', function(event) {
            event.preventDefault();

            if($('.section-block').length > 1){
                $('.section-block').last().remove();
                $('.section-block').last().scrollTo();
            }
        });




    });
</script>
@stop