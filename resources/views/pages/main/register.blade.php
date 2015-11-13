{{--
|--------------------------------------------------------------------------
| Admin registration
|--------------------------------------------------------------------------
|
| This template is used when showing admin registration form.
|
--}}

@extends('layout.admin.master')

@section('content')

    <div class="row">
        <div class="col-xs-12 col-md-4" id="main">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <h3 class="sub-header">Register</h3>

                    @include('errors.list')

                    {!! Form::open(['route' => 'admin.register', 'class' => 'form-horizontal', 'id' => 'admin-register-form']) !!}

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::label('role_id', 'Role') !!}
                            {!! Form::select('role_id', ['2' => 'Administrator', '3' => 'Moderator'], ['class' => 'form-control input-sm', 'id' => 'role-id']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::label('first_name', 'First Name') !!}
                            {!! Form::text('first_name', old('name'), ['class' => 'form-control input-sm', 'id' => 'first-name']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::label('last_name', 'Last Name') !!}
                            {!! Form::text('last_name', old('name'), ['class' => 'form-control input-sm', 'id' => 'last-name']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::label('email', 'E-Mail Address') !!}
                            {!! Form::email('email', old('email'), ['class' => 'form-control input-sm', 'id' => 'email']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::label('password', 'Password') !!}
                            {!! Form::password('password', ['class' => 'form-control input-sm', 'id' => 'password']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::label('password_confirmation', 'Confirm Password') !!}
                            {!! Form::password('password_confirmation', ['class' => 'form-control input-sm', 'id' => 'password-confirmation']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::submit('Register', ['class' => 'btn btn-primary', 'id' => 'register-btn']) !!}
                        </div>
                    </div>

            {!! Form::close() !!}

            </div>
        </div>
    </div>

@stop