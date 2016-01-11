@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-4 col-md-offset-4 main" id="password-main">

        {!! Form::open(['route' => 'password.post.email', 'files' => true, 'class' => 'form-horizontal', 'id' => 'admin-password-send-form']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Forgotten Password</h4>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <div class="form-group">
                    <div class="col-xs-12 col-md-10">
                        {!! Form::label('email_label', 'E-Mail Address / Username') !!}
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-key"></i></span>
                            {!! Form::email('email', old('email'), ['class' => 'form-control input-sm', 'id' => 'email']) !!}
                        </div>
                    </div>
                </div>

                @include('pages/main/partials/submit_button', [
                    'submitBtnText' => 'Send Password Reset',
                    'direction'     => 'pull-left',
                    'class'		    => 'btn-custom btn-sm',
                    'id'			=> 'send-btn'
                ])

            </div>
        </div>

        {!! Form::close() !!}

    </div>

@stop