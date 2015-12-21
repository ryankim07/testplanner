{{--
|--------------------------------------------------------------------------
| Admin reset password
|--------------------------------------------------------------------------
|
| This template is used when showing password reset form.
|
--}}

@extends('layout.admin.master')

@section('content')

    <div class="row">
        <div class="col-xs-12 col-md-4" id="main">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <h3 class="sub-header">Reset Password</h3>

                    @include('errors.list')

                    {!! Form::open(['route' => 'password.post.reset', 'class' => 'form-horizontal', 'id' => 'password-reset-form']) !!}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::label('email', 'E-Mail Address', ['class' => 'col-md-4 control-label']) !!}
							{!! Form::email('email', old('email'), ['class' => 'form-control', 'id' => 'email']) !!}
                        </div>
					</div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::label('password', 'Password', ['class' => 'col-md-4 control-label']) !!}
                            {!! Form::password('password', null, ['class' => 'form-control', 'id' => 'password']) !!}
                        </div>
					</div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-md-4 control-label']) !!}
                            {!! Form::password('password_confirmation', null, ['class' => 'form-control', 'id' => 'password']) !!}
                        </div>
					</div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-8">
                            {!! Form::submit('Reset Password', ['class' => 'btn btn-primary', 'id' => 'continue-btn']) !!}
						</div>
					</div>

                    {!! Form::close() !!}

            </div>
        </div>
    </div>

@stop