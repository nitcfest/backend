@extends('layouts.guest')

@section('title')
Sign up - CMS
@stop

@section('content')

<div style="height:50px;"></div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Fill in your details.</h3>
            </div>
            <div class="panel-body">
                <form role="form" method="POST" action="{{{ URL::route('manager_signup') }}}" accept-charset="UTF-8">
                	<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="Name" name="name" type="text" value="{{Input::old('name') ?: ''}}" autofocus required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Email" name="email" type="email" value="{{Input::old('email') ?: ''}}" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Roll Number" name="roll_no" type="text" value="{{Input::old('roll_no') ?: ''}}" required>
                        </div>
                        <div class="form-group">
                            <input  class="form-control" placeholder="Password" name="password" type="password" required>
                        </div>
                        <div class="form-group">
                            <input  class="form-control" placeholder="Confirm Password" name="password_confirmation" type="password" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <select name="role" class="form-control">
                                <option value="2">Event Manager</option>
                                <option value="8">Proofreader</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Event Name" name="event_name" type="text" value="{{Input::old('event_name') ?: ''}}">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Event Code" name="event_code" value="{{Input::old('event_code') ?: ''}}" type="text">
                        </div>
                        <div class="form-group">
                            <select name="category_id" class="form-control">
                                @foreach ($event_categories as $category)
                                        <option value="{{ $category->id }}" @if($category->id == Input::old('category_id')) selected="selected" @endif>@if($category->parent_id === 0) Root :: @endif {{ $category->name }}</option>
                                @endforeach
                            </select>                            
                        </div>


                        <button type="submit" class="btn btn-lg btn-info btn-block">Signup</button>
                    </fieldset>
                </form>

                <br>Have an account? <a href="{{ URL::route('manager_login')}}">Log in</a>.

                @if (Session::get('errors'))
                    <br>
                    <div class="alert alert-error alert-danger">{{ Session::get('errors') }}</div>
                @endif

            </div>
        </div>
    </div>
</div>

@stop