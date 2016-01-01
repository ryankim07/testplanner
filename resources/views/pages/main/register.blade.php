{{--
|--------------------------------------------------------------------------
| Admin registration
|--------------------------------------------------------------------------
|
| This template is used when showing admin registration form.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="register-main">

        {!! Form::open(['route' => 'auth.post.register', 'class' => 'form-horizontal', 'id' => 'admin-register-form']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left"><h3>Add new user</h3></div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <div class="form-group">
                    <div class="col-xs-12 col-md-2">
                        {!! Form::label('assign_role', 'Role') !!}
                        {!! Form::select('assign_role', ['2' => 'Administrator', '3' => 'User'], null, ['class' => 'form-control input-sm', 'id' => 'role-id']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-4">
                        {!! Form::label('first_name', 'First Name') !!}
                        {!! Form::text('first_name', old('name'), ['class' => 'form-control input-sm', 'id' => 'first-name']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-4">
                        {!! Form::label('last_name', 'Last Name') !!}
                        {!! Form::text('last_name', old('name'), ['class' => 'form-control input-sm', 'id' => 'last-name']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-4">
                        {!! Form::label('email', 'E-Mail Address') !!}
                        {!! Form::email('email', old('email'), ['class' => 'form-control input-sm', 'id' => 'email']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-4">
                        {!! Form::label('password', 'Password') !!}
                        {!! Form::password('password', ['class' => 'form-control input-sm', 'id' => 'password']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-4">
                        {!! Form::label('password_confirmation', 'Confirm Password') !!}
                        {!! Form::password('password_confirmation', ['class' => 'form-control input-sm', 'id' => 'password-confirmation']) !!}
                    </div>
                </div>

                @include('pages/main/partials/submit_button', [
                    'submitBtnText' => 'Save',
                    'direction'     => 'pull-left',
                    'class'		    => 'btn-success',
                    'id'			=> 'save-btn'
                ])

            </div>
        </div>

        {!! Form::close() !!}

    </div>

@stop