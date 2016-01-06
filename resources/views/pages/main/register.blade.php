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
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <i class="fa fa-user-plus fa-4x header-icon"></i>
                        <h3>Add new user</h3>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @include('pages/main/partials/user_info', [
                    'column'               => 'col-md-4',
                    'user'                 => '',
                    'rolesOptions'         => $rolesOptions,
                    'rolesSelectedOptions' => ''
                ])

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