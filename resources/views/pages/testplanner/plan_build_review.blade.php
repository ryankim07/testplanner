{{--
|--------------------------------------------------------------------------
| Review new created plan
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12" id="main">

        {!! Form::open(['action' => ['PlansController@save'], 'class' => '', 'id' => 'plan-review-form']) !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left"><h3>Review</h3></div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <div class="row">
                    <h5>Plan Description</h5>
                    <ul class="list-unstyled">
                        <li>{!! $plan['description'] !!}</li>
                    </ul>
                </div>

                <div class="row">
                    <h5>Tickets</h5>
                    @foreach($tickets as $ticket)

                        <ul class="list-unstyled">
                            <li>Description: {!! $ticket['description'] !!}</li>
                            <li>Objective: {!! $ticket['objective'] !!}</li>
                            <li>Test Steps: {!! nl2br($ticket['test_steps']) !!}</li>
                        </ul>

                    @endforeach
                </div>

                <div class="row">
                    <h5>Browser Testers</h5>
                    <ul class="list-unstyled">

                        @foreach($testers as $tester)

                            <li>{!! $tester['first_name'] !!} -> {!! $tester['browser'] !!}</li>

                        @endforeach

                    </ul>
                </div>
            </div>
        </div>

        @include('pages/main/partials/submit_button', [
            'submitBtnText' => 'Finalize',
            'direction'     => 'pull-right',
            'class'		    => 'btn-success btn-lg',
            'id'            => 'continue-btn'
        ])

        {!! Form::close() !!}

    </div>

@stop