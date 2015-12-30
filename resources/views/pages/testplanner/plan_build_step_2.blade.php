{{--
|--------------------------------------------------------------------------
| Step 2
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12" id="main">

        {!! Form::open(['route' => 'ticket.store', 'class' => '', 'id' => 'ticket-build-form']) !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left"><h3>Step 2 of 3 - Add tickets to be tested</h3></div>
                    <div class="pull-right"><h5>All fields required</h5></div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @include('pages/testplanner/partials/tickets', [
                    'ticket' => null,
                    'mode'   => 'create'
                ])

            </div>
        </div>

        @include('pages/main/partials/submit_button', [
            'submitBtnText' => 'Continue',
            'direction'     => 'pull-right',
            'class'		    => 'btn-success btn-lg',
            'id'            => 'submit-tickets-btn'
        ])

        {!! Form::close() !!}

    </div>

@stop