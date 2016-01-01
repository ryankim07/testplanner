{{--
|--------------------------------------------------------------------------
| Step 2
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="build-step-2-main">

        {!! Form::open(['route' => 'ticket.store', 'id' => 'ticket-build-form']) !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Step 2 of 3 - Add tickets to be tested</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">45%</div>
                        </div>
                    </div>
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

    @include('pages/testplanner/partials/step_2_js')

@stop