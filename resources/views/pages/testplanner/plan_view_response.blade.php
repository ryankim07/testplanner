{{--
|--------------------------------------------------------------------------
| Admin view plan
|--------------------------------------------------------------------------
|
| This template is used when viewing customer.
|
--}}

@extends('layout.admin.master')

@section('content')

<div class="col-xs-12 col-md-12" id="main">

    {!! Form::open(['route' => 'plan.view.response', 'class' => 'enroll-form', 'id' => 'plan-user-response-form']) !!}
    {!! Form::hidden('plan', json_encode($plan)) !!}
    {!! Form::hidden('plan_id', $plan['id'], ['id' => 'plan_id']) !!}
    {!! Form::hidden('ticket_resp_id', $plan['ticket_resp_id']) !!}

    <div class="page-header">
        <div class="pull-left">{!! $plan['description'] !!}</div>
        <div class="pull-right">
            {!! Form::select('tester', $testers, $userId, ['id' => 'tester']) !!}
        </div>
    </div>

    @include('errors.list')

    <div class="col-xs-8 col-md-8">

        @foreach($plan['tickets'] as $ticket)

            <div class="panel panel-default ticket-panel">
                <div class="panel-body">

                    {!! Form::hidden('ticket_id', $ticket['id'], ['class' => 'ticket_id']) !!}

                    <ul class="list-unstyled">
                        <li>
                            <span class="ticket-header">Ticket</span>
                            <ul class="list-unstyled">
                                <li>
                                    <span class="ticket-description">{!! $ticket['description'] !!}</span>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span class="ticket-header">Objective</span>
                            <ul class="list-unstyled">
                                <li>
                                    <span>{!! $ticket['objective'] !!}</span>
                                </li>
                            </ul>
                        <li>
                            <span class="ticket-header">Steps to test</span>
                            <ul class="list-unstyled">
                                <li>
                                    <span>{!! nl2br($ticket['test_steps']) !!}</span>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <?php $notesResponse = isset($ticket['notes_response']) ? $ticket['notes_response'] : null; ?>

                                <span class="ticket-header">Notes</span>

                            <ul class="list-unstyled">
                                <li>{!! $notesResponse !!}</li>
                            </ul>
                        </li>
                        <li>
                            <span class="ticket-header">Status</span>
                            <ul class="list-unstyled">
                                <li>
                                    <?php
                                        $passed = '';
                                        $failed = '';

                                        if (isset($ticket['test_status'])) {
                                            $passed = $ticket['test_status'] == 1 ? true : '';
                                            $failed = $ticket['test_status'] == 0 ? true : '';
                                        }
                                    ?>

                                    @if($passed)
                                        <span>Passed</span>
                                    @elseif($failed)
                                        <span>Failed</span>
                                    @endif
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

        @endforeach
    </div>

    <div class="col-xs-4 col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li>
                        <span class="ticket-header">People</span>
                        <ul class="list-unstyled">
                            <li>
                                <span>Reporter: {!! $plan['reporter'] !!}</span>
                            </li>
                            <li>
                                <span>Assignee: {!! $plan['assignee'] !!}</span>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <span class="ticket-header">Dates</span>
                        <ul class="list-unstyled">
                            <li>
                                <span>Created: {!! $plan['created_at'] !!}</span>
                            </li>
                            <li>
                                <span>Updated: {!! $plan['updated_at'] !!}</span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {!! Form::close() !!}

</div>

<script type="text/javascript">

    $(document).ready(function() {
        // Open viewer for dropdown
        $('#tester').on('change', function () {
            var route = "{!! URL::route('plan.view.response', null) !!}";
            var userId = $(this).val();
            var planId = $('#plan_id').val();

            window.location.href = route + '/' + planId + "/" + userId;
        });
    });

</script>

@stop