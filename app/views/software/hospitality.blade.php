@extends('layouts.user')

@section('title')
Hospitality
@stop

@section('head')
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Hospitality</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->



<div class="row">
    <div class="col-md-6">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-male fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{count($males)}}</div>
                        <div class="big">Male</div>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($males as $male)
                   <?php $i++; ?>
                   <tr>
                       <td>{{Config::get('app.id_prefix').$male->id}}</td>
                       <td>{{$male->name }}</td>
                       <td>{{$male->phone }}</td>
                   </tr>
                @endforeach
            </tbody>
        </table>      
    </div>
    <div class="col-md-6">

        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-female fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{count($females)}}</div>
                        <div class="big">Female</div>
                    </div>
                </div>
            </div>
        </div>
        
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach ($females as $female)
                   <?php $i++; ?>
                   <tr>
                       <td>{{Config::get('app.id_prefix').$female->id}}</td>
                       <td>{{$female->name }}</td>
                       <td>{{$female->phone }}</td>
                   </tr>
                @endforeach
            </tbody>
        </table>      
    </div>

</div>
<!-- /.row -->

@stop

@section('scripts')


@stop