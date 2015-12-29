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

    {!! Form::open(['route' => 'plan.save.user.response', 'class' => 'enroll-form', 'id' => 'plan-user-response-form']) !!}
    {!! Form::hidden('plan', json_encode($plan)) !!}
    {!! Form::hidden('ticket_resp_id', $plan['ticket_resp_id']) !!}

    <div class="page-header">
        <h3>{!! $plan['description'] !!}</h3>
    </div>

    @include('errors.list')

    <div class="col-xs-8 col-md-8">

        <?php $i = 1; ?>
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

                            <span>Notes</span>

                            <ul class="list-unstyled">
                                <li>
                                    {!! Form::textarea('notes_response', $notesResponse, ['class' => 'notes_response', 'size' => '100x10']) !!}
                                </li>
                            </ul>
                        </li>
                        <li>
                            <?php
                                $passed = '';
                                $failed = '';

                                if (isset($ticket['test_status'])) {
                                    $passed = $ticket['test_status'] == 1 ? true : '';
                                    $failed = $ticket['test_status'] == 0 ? true : '';
                                }
                            ?>

                            {!! Form::label('test_status_label', 'Passed') !!}
                            {!! Form::radio('test_status_' . $i, 1, $passed, ['class' => 'test_status']) !!}
                            {!! Form::label('test_status_label', 'Failed') !!}
                            {!! Form::radio('test_status_' . $i, 0, $failed, ['class' => 'test_status']) !!}
                        </li>
                    </ul>
                </div>
            </div>

        <?php $i++; ?>
        @endforeach

        @include('pages/main/partials/submit_button', ['submitBtnText' => 'Submit Response'])
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
        $('#continue-btn').on('click', function(e) {
            var tickets = [];

            $('.ticket-panel').each(function () {
                // Create ticket object
                tickets.push({
                    "id": $(this).find('.ticket_id').val(),
                    "test_status": $(this).find('input[type="radio"]:checked').val(),
                    "notes_response": $(this).find('.notes_response').val()
                });
            });

            // Create hidden field
            var input = $("<input>")
                .attr("type", "hidden")
                .attr("name", "tickets-obj").val(JSON.stringify(tickets));

            $('form').append($(input));
        });
    });

</script>

@stop