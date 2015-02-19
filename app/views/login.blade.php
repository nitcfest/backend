@extends('layouts.guest')

@section('title')
Login - CMS
@stop

@section('content')

<div style="height:50px;"></div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Please Sign In</h3>
            </div>
            <div class="panel-body">
                <form role="form" method="POST" action="{{{ URL::route('manager_login') }}}" accept-charset="UTF-8">
                	<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <fieldset>
                        <div class="form-group">
                            <input tabindex="1" class="form-control" placeholder="Email" name="email" type="email" autofocus>
                        </div>
                        <div class="form-group">
                            <input tabindex="2" class="form-control" placeholder="Password" name="password" type="password" value="">
                        </div>

                        <button tabindex="3" type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                    </fieldset>
                </form>

                @if (Session::get('error'))
                    <br>
                    <div class="alert alert-error alert-danger">{{{ Session::get('error') }}}</div>
                @endif

                @if (Session::get('notice'))
                    <br>
                    <div class="alert alert-info">{{{ Session::get('notice') }}}</div>
                @endif
                
            </div>
        </div>
    </div>
</div>

@stop