{{--
|--------------------------------------------------------------------------
| Mian login
|--------------------------------------------------------------------------
|
| This template is used when showing login form.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="row">
        <div class="col-xs-12 col-md-4 col-md-offset-4">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <h3 class="sub-header">Login</h3>

                    @include('errors.list')

                    {!! Form::open(['route' => $formAction, 'class' => 'form-horizontal', 'id' => 'auth-login-form']) !!}

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::label('email', 'E-Mail Address / Username') !!}
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
                            {!! Form::submit('Login', ['class' => 'btn btn-primary', 'id' => 'continue-btn']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::checkbox('remember', 1, false, ['class' => '', 'aria-required' => 'true', 'id' => 'remember']) !!}
                            {!! Form::label('Remember me') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Html::linkRoute('password.email', 'Forgot Your Password?') !!}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

@stop
