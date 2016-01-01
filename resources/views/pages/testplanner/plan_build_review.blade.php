{{--
|--------------------------------------------------------------------------
| Review new created plan
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="review-main">

        {!! Form::open(['action' => ['PlansController@save'], 'class' => '', 'id' => 'plan-review-form']) !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Review</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Completed</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <div class="row nested-block">
                    <h5>Plan Description</h5>
                    <ul class="list-unstyled">
                        <li>{!! $plan['description'] !!}</li>
                    </ul>
                </div>

                <div class="row nested-block">
                    <h5>Tickets</h5>
                    @foreach($tickets as $ticket)
                        <ul class="list-unstyled">
                            <li>Description: {!! $ticket['description'] !!}</li>
                            <li>Objective: {!! $ticket['objective'] !!}</li>
                            <li>Test Steps: {!! nl2br($ticket['test_steps']) !!}</li>
                        </ul>
                    @endforeach
                </div>

                <div class="row nested-block">
                    <h5>Browser Testers</h5>
                    <ul class="list-unstyled">
                        @foreach($testers as $tester)
                            <li>{!! Html::image('images/' . $tester['browser'] . '.png', 'Chrome', ['width' => 32, 'height' => 32]) !!}  {!! $tester['first_name'] !!}</li>
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