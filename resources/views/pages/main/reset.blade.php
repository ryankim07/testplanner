{{--
|--------------------------------------------------------------------------
| Admin reset password
|--------------------------------------------------------------------------
|
| This template is used when showing password reset form.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-4 col-md-offset-4 main" id="reset-main">

        {!! Form::open(['route' => 'password.post.reset', 'class' => 'form-horizontal', 'id' => 'password-reset-form']) !!}
        {!! Form::hidden('token', $token) !!}

        <div class="panel panel-info">
            <div class="panel-heading">
                <h4>Reset Password</h4>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('email_label', 'E-Mail Address') !!}
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
                            {!! Form::email('email', old('email'), ['class' => 'form-control input-sm', 'id' => 'email']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('password_label', 'Password') !!}
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-key"></i></span>
                            {!! Form::password('password', ['class' => 'form-control input-sm', 'id' => 'password']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('password_confirmation_label', 'Confirm Password') !!}
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-key"></i></span>
                            {!! Form::password('password_confirmation', ['class' => 'form-control input-sm', 'id' => 'password_confirmation']) !!}
                        </div>
                    </div>
                </div>

                @include('pages/main/partials/submit_button', [
                    'submitBtnText' => 'Reset Password',
                    'direction'     => 'pull-left',
                    'class'		    => 'btn-primary',
                    'id'			=> 'reset-btn'
                ])

            </div>

        {!! Form::close() !!}

    </div>

@stop