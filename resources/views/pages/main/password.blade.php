@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-4 main" id="password-main">

        {!! Form::open(['route' => 'password.post.email', 'files' => true, 'class' => 'form-horizontal', 'id' => 'admin-password-send-form']) !!}

        <div class="panel panel-primary col-md-8">
            <div class="panel-body">
                <h3 class="sub-header">Forgotten Password</h3>

                @include('errors.list')

                <div class="form-group">
                    <div class="col-xs-12 col-md-8">
                        {!! Form::label('email_label', 'E-Mail Address / Username') !!}
                        {!! Form::email('email', old('email'), ['class' => 'form-control input-sm', 'id' => 'email']) !!}
                    </div>
                </div>

                @include('pages/main/partials/submit_button', [
                    'submitBtnText' => 'Send Password Reset Link',
                    'direction'     => 'pull-left',
                    'class'		    => 'btn-primary',
                    'id'			=> 'send-btn'
                ])

            </div>
        </div>

        {!! Form::close() !!}

    </div>

@stop