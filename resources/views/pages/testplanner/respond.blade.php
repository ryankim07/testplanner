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

    <div class="col-xs-12 col-md-12 main" id="respond-main">

        {!! Form::open(['route' => 'plan.save.user.response', 'class' => 'form-horizontal', 'id' => 'plan-user-response-form']) !!}
        {!! Form::hidden('plan', json_encode($plan)) !!}
        {!! Form::hidden('ticket_resp_id', $plan['ticket_resp_id']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-10 col-md-10">
                        <i class="fa fa-pencil-square-o fa-3x header-icon"></i>
                        <h4>Respond - {!! $plan['description'] !!}</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                @include('errors.list')

                <div class="row nested-block">
                    <legend>Plan Details</legend>
                    <div class="col-xs-12 col-md-4">
                        <div class="form-group">
                            <p>Admin: <strong>{!! $plan['reporter'] !!}</strong></p>
                            <p>Assignee: <strong>{!! $plan['assignee'] !!}</strong></p>
                            <p>Status:

                                <?php
                                    if($plan['ticket_status'] == 'complete') {
                                        $trLabel = 'label-default';
                                    } else if($plan['ticket_status'] == 'progress') {
                                        $trLabel = 'label-warning';
                                    } else {
                                        $trLabel = 'label-success';
                                    }
                                ?>

                                <span class="label {!! $trLabel !!}">{!! $plan['ticket_status'] !!}</span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-8">
                        <div class="form-group">
                            <p>Created: <strong>{!! $plan['created_at'] !!}</strong></p>
                            <p>Updated: <strong>{!! $plan['updated_at'] !!}</strong></p>
                        </div>
                    </div>
                </div>
                @foreach($plan['tickets'] as $ticket)
                    <div class="row nested-block ticket-panel">
                        <legend>Ticket - {!! Html::link(isset($ticket['description_url']) ? $ticket['description_url'] : '#', $ticket['description'], ['target' => '_blank', 'title' => 'Click to view issue in Jira']) !!}</legend>
                        <div class="col-xs-12 col-md-6">
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
                            <legend>Status</legend>
                            <div class="radio">
                                <?php
                                    $passed = '';
                                    $failed = '';

                                    if (isset($ticket['test_status'])) {
                                        $passed = $ticket['test_status'] == 1 ? true : '';
                                        $failed = $ticket['test_status'] == 0 ? true : '';
                                    }
                                ?>

                                <label>
                                    {!! Form::radio('test_status[' . $ticket["id"] . ']', 1, $passed, ['class' => 'test_status']) !!}
                                    Passed
                                </label>
                                <label>
                                    {!! Form::radio('test_status[' . $ticket["id"] . ']', 0, $failed, ['class' => 'test_status']) !!}
                                    Failed
                                </label>

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
                @endforeach
            </div>
        </div>

        @include('pages/main/partials/submit_button', [
            'submitBtnText' => 'Submit Response',
            'direction'     => 'pull-left',
            'class'		    => 'btn-custom',
            'id'			=> 'respond-btn'
        ])

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            var totalResponses = 0;
            $('#respond-main .ticket-panel').each(function() {
                var notesResponse = $(this).find('.notes-response');

                if (notesResponse.val() != '') {
                    totalResponses++;
                }
            });

            if (totalResponses > 0) {
                $('#respond-btn').prop('value', 'Update Response')
            }
        });

    </script>

@stop