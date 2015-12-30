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

<div class="col-xs-12 col-md-12" id="main">
    <div class="page-header">
        <div class="pull-left"><h4>{!! $plan['description'] !!}</h4></div>
        <div class="pull-right">
            {!! Form::select('tester', $testers, $userId, ['class' => 'form-control input-sm', 'id' => 'tester']) !!}
        </div>
    </div>

    @include('errors.list')

    {!! Form::open(['route' => 'plan.view.response', 'class' => 'enroll-form', 'id' => 'plan-user-response-form']) !!}
    {!! Form::hidden('plan', json_encode($plan)) !!}
    {!! Form::hidden('plan_id', $plan['id'], ['id' => 'plan_id']) !!}
    {!! Form::hidden('ticket_resp_id', $plan['ticket_resp_id']) !!}

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
                    <p><span class="ticket-description">{!! $ticket['description'] !!}</span></p>
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

    {!! Form::close() !!}

</div>

@stop