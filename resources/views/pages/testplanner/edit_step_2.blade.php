{{--
|--------------------------------------------------------------------------
| Step 2
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="build-step-2-main">

        {!! Form::model($ticketData, ['method' => 'PATCH', 'route' => ['ticket.update', $ticketData['id']], 'id' => 'ticket-edit-form']) !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Step 2 of 3 - Edit tickets to be tested</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @include('pages/testplanner/partials/tickets', ['ticket' => $ticketData])

            </div>
        </div>

        @include('pages/main/partials/submit_button', [
            'submitBtnText' => 'Update',
            'direction'     => 'pull-right',
            'class'		    => 'btn-success btn-lg',
            'id'            => 'continue-btn'
        ])

        {!! Form::close() !!}

    </div>

    @include('pages/testplanner/partials/step_2_js')

@stop