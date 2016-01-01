{{--
|--------------------------------------------------------------------------
| Review new created plan
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="review-main">

        {!! Form::open(['action' => ['PlansController@save'], 'id' => 'plan-review-form']) !!}

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
                    <legend>Plan Description</legend>
                    <a href="{!! URL::route('plan.edit') !!}" class="cog"><span class="glyphicon glyphicon-cog"></span></a>
                    <ul class="list-unstyled">
                        <li><h5>{!! $plan['description'] !!}</h5></li>
                    </ul>
                </div>
                @foreach($tickets as $ticket)
                    <div class="row nested-block">
                        <legend>Ticket</legend>
                        <a href="{!! URL::route('ticket.edit') !!}" class="cog"><span class="glyphicon glyphicon-cog"></span></a>
                        <ul class="list-unstyled">
                            <li><h4><span class="label label-default">Description</span></h4><h5>{!! $ticket['description'] !!}</h5></li>
                            <li><h4><span class="label label-primary">Objective</span></h4><h5>{!! $ticket['objective'] !!}</h5></li>
                            <li><h4><span class="label label-info">Test Steps</span></h4><h5>{!! nl2br($ticket['test_steps']) !!}</h5></li>
                        </ul>
                    </div>
                @endforeach
                <div class="row nested-block">
                    <legend>Browser Testers</legend>
                    <a href="{!! URL::route('tester.edit') !!}" class="cog"><span class="glyphicon glyphicon-cog"></span></a>
                    @foreach($testers as $tester)
                    <div class="text-center review-testers">
                        {!! Html::image('images/' . $tester['browser'] . '.png', 'Chrome') !!}<h5><span class="caption">{!! $tester['first_name'] !!}</span></h5>
                    </div>
                    @endforeach
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