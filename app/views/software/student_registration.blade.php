@extends('layouts.user')

@section('title')
Student Registrations
@stop

@section('head')
<link href="{{URL::to('/')}}/bower_components/datatables/css/dataTables.bootstrap.css" rel="stylesheet">

<style>
.dataTables_filter input { width: 400px !important }

</style>
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Student Registrations</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">

        <h3>Selected for Registration</h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>College</th>
                    <th>Reg.</th>
                    <th>Hosp.</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody id="registration-selected">
                <tr>
                    <td class="no-row" colspan="8"> No students selected yet. </td>
                </tr>
            </tbody>
        </table>
        <button type="button" id="action-start-registration" class="disabled btn btn-success btn-lg">Start Registration <span class="glyphicon glyphicon-chevron-right"></span></button>

        <br><br>

        <p>Search using {{Config::get('app.main_name')}} ID, name, email, phone or college name.</p>
        
        <table class="table table-striped table-hover" id="registration_table" style="display:none;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>College</th>
                    <th>Reg.</th>
                    <th>Hosp.</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody id="registration-rows">
                <?php $i=0; ?>
                @foreach ($registrations as $registration)
                   <?php $i++; ?>
                   <tr data-id="{{$registration->id}}">
                       <td>{{Config::get('app.id_prefix').$registration->id}}</td>
                       <td>{{$registration->name }}</td>
                       <td>{{$registration->email }}</td>
                       <td>{{$registration->phone }}</td>
                       <td>@if ($registration->college) {{$registration->college->name }} @endif</td>
                       <td>@if ($registration->registration_confirm == 1) Confirmed @else No @endif</td>
                       <td>@if ($registration->registration_confirm == 1 && $registration->hospitality_confirm == 1) Confirmed @else No @endif</td>
                       <td><button type="button" class="action-select-row btn btn-default btn-xs"><span class="glyphicon glyphicon-plus"></span> Add</button></td>
                   </tr>
                @endforeach
            </tbody>
        </table>      


    </div>
</div>

@stop


@section('scripts')
<script src="{{URL::to('/')}}/bower_components/datatables/js/jquery.dataTables.min.js"></script>
<script src="{{URL::to('/')}}/bower_components/datatables/js/dataTables.bootstrap.min.js"></script>

<script>
    $(function() {
        var table = $('#registration_table').DataTable({
                      "columns": [null,
                                  null,
                                  null,
                                  null,
                                  null,
                                  null,
                                  null,
                                  { "orderable": false,  "searchable": false }
                                 ],
                  });
        $('#registration_table').show();

        $('#registration-rows').on('click', '.action-select-row', function(event) {
            event.preventDefault();
            var clone = $(this).parents('tr').clone();

            clone.find('td').last()
                 .html('<button type="button" class="action-deselect-row btn btn-default btn-xs"><span class="glyphicon glyphicon-minus"></span> Remove</button>');

            table.row( $(this).parents('tr') )
                 .remove()
                 .draw();


            $('.no-row').hide();
            $('#action-start-registration').removeClass('disabled');

            clone.appendTo('#registration-selected');
        });

        $('#registration-selected').on('click', '.action-deselect-row', function(event) {
            event.preventDefault();

            var row_data = [];
            $(this).parents('tr').children('td').each(function(){
                row_data.push($(this).html());
            });

            row_data[row_data.length-1] = '<button type="button" class="action-select-row btn btn-default btn-xs"><span class="glyphicon glyphicon-plus"></span> Add</button>';

            table.row.add( row_data )
                 .draw();

            $(this).parents('tr').remove();

            if($('#registration-selected').children('tr').length == 1){
                $('.no-row').show();
                $('#action-start-registration').addClass('disabled');
            }

        });


        $('#action-start-registration').on('click', function(event) {
            event.preventDefault();
            var selected = [];
            $('#registration-selected').children('tr').each(function(){
                if($(this).data('id'))
                    selected.push($(this).data('id'));
            });

            location.href = '{{ URL::route('software_student_registration_do')}}?ids='+JSON.stringify(selected);                        

        });

    });
</script>

@stop