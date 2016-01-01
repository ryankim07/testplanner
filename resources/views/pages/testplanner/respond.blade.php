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

    <div class="col-xs-12 col-md-12 respond main" id="respond-main">

        {!! Form::open(['route' => 'plan.save.user.response', 'class' => 'enroll-form', 'id' => 'plan-user-response-form']) !!}
        {!! Form::hidden('plan', json_encode($plan)) !!}
        {!! Form::hidden('ticket_resp_id', $plan['ticket_resp_id']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left"><h3>{!! $plan['description'] !!}</h3>
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

                <?php $i = 1; ?>
                @foreach($plan['tickets'] as $ticket)
                    <div class="row nested-block ticket-panel">
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <legend>Ticket</legend>
                                <span class="ticket-description">{!! $ticket['description'] !!}</span>
                            </div>
                            <div class="form-group">
                                <legend>Objective</legend>
                                <span>{!! $ticket['objective'] !!}</span>
                            </div>
                            <div class="form-group">
                                <legend>Steps to test</legend>
                                <span>{!! nl2br($ticket['test_steps']) !!}</span>
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

                                {!! Form::label('test_status_label', 'Passed', ['class' => 'radio-inline']) !!}
                                {!! Form::radio('test_status_' . $i, 1, $passed, ['class' => 'test_status']) !!}

                                {!! Form::label('test_status_label', 'Failed', ['class' => 'radio-inline']) !!}
                                {!! Form::radio('test_status_' . $i, 0, $failed, ['class' => 'test_status']) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <legend>Notes</legend>

                                <?php $notesResponse = isset($ticket['notes_response']) ? $ticket['notes_response'] : null; ?>

                                {!! Form::textarea('notes_response', $notesResponse, ['class' => 'form-control notes-response', 'rows' => '15']) !!}
                            </div>
                        </div>
                        {!! Form::hidden('ticket_id', $ticket['id'], ['class' => 'ticket-id']) !!}
                    </div>

                    <?php $i++; ?>
                @endforeach

                @include('pages/main/partials/submit_button', [
                    'submitBtnText' => 'Submit Response',
                    'direction'     => 'pull-left',
                    'class'		    => 'btn-primary',
                    'id'			=> 'respond-btn'
                ])

            </div>
        </div>

        {!! Form::close() !!}

    </div>

@stop