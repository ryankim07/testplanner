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

    <div class="col-xs-12 col-md-4 main" id="reset-main">

        {!! Form::open(['route' => 'password.post.reset', 'class' => 'form-horizontal', 'id' => 'password-reset-form']) !!}

        <div class="panel panel-primary col-md-8">
            <div class="panel-body">
                <h3 class="sub-header">Reset Password</h3>

                @include('errors.list')

                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('email_label', 'E-Mail Address') !!}
                        {!! Form::email('email', old('email'), ['class' => 'form-control input-sm', 'id' => 'email']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('password_label', 'Password') !!}
                        {!! Form::password('password', ['class' => 'form-control input-sm', 'id' => 'password']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('password_confirmation_label', 'Confirm Password') !!}
                        {!! Form::password('password_confirmation', ['class' => 'form-control input-sm', 'id' => 'password_confirmation']) !!}
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