{{--
|--------------------------------------------------------------------------
| Admin view plan
|--------------------------------------------------------------------------
|
| This template is used when viewing customer.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="view-response-main">

        {!! Form::open(['route' => 'plan.view.response', 'class' => 'enroll-form', 'id' => 'plan-user-response-form']) !!}
        {!! Form::hidden('plan', json_encode($plan)) !!}
        {!! Form::hidden('plan_id', $plan['id'], ['id' => 'plan_id']) !!}
        {!! Form::hidden('ticket_resp_id', $plan['ticket_resp_id']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-10">
                        {!! Html::image('images/plan.png', 'Plan', ['class' => 'plan-img', 'width' => 64, 'height' => 64]) !!}
                        <h3>{!! $plan['description'] !!}</h3>
                    </div>
                    <div class="col-md-2">
                        {!! Form::select('tester', $testers, null, ['class' => 'form-control input-sm', 'id' => 'view-tester', 'data-url' => route('plan.view.response', ['id' => null])]) !!}
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <div class="row nested-block">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <legend>People</legend>
                            <p><span>Reporter: {!! $plan['reporter'] !!}</span></p>
                            <p><span>Assignee: {!! $plan['assignee'] !!}</span></p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <legend>Dates</legend>
                            <p><span>Created: {!! $plan['created_at'] !!}</span></p>
                            <p><span>Updated: {!! $plan['updated_at'] !!}</span></p>
                        </div>
                    </div>
                </div>
                @foreach($plan['tickets'] as $ticket)
                    <div class="row nested-block ticket-panel">
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <legend>Ticket</legend>
                                <p class="ticket-description">{!! Html::link($ticket['description_url'], $ticket['description'], ['target' => '_blank', 'title' => 'Click to view issue in Jira']) !!}</p>
                            </div>
                            <div class="form-group">
                                <legend>Objective</legend>
                                <p><span>{!! $ticket['objective'] !!}</span></p>
                            </div>
                            <div class="form-group">
                                <legend>Steps to test</legend>
                                <p><span>{!! nl2br($ticket['test_steps']) !!}</span></p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="form-group">
                                <legend>Status</legend>

                                <?php
                                if (isset($ticket['test_status'])) {
                                    $passed = $ticket['test_status'] == 1 ? true : '';
                                    $failed = $ticket['test_status'] == 0 ? true : '';
                                }
                                ?>

                                <p><span>
                                    @if($passed)
                                        Passed
                                    @elseif($failed)
                                        Failed
                                    @endif
                                </span></p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <legend>Notes</legend>

                                {!! isset($ticket['notes_response']) ? nl2br($ticket['notes_response']) : null !!}

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {!! Form::close() !!}

    </div>

@stop