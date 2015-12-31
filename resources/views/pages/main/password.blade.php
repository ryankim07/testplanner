@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-4 main" id="password-main">

        {!! Form::open(['route' => 'password.email', 'files' => true, 'class' => 'form-horizontal', 'id' => 'admin-password-send-form']) !!}

        <div class="panel panel-primary">
            <div class="panel-body">
                <h3 class="sub-header">Forgotten Password</h3>

                @include('errors.list')

                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('email', 'E-Mail Address / Username') !!}
                        {!! Form::email('email', old('email'), ['class' => 'form-control input-sm', 'id' => 'email']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::submit('Send Password Reset Link', ['class' => 'btn btn-primary', 'id' => 'continue-btn']) !!}
                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}

    </div>

@stop