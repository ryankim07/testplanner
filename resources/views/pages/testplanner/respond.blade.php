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

        {!! Form::open(['route' => 'ticket.save.response', 'class' => 'form-horizontal', 'id' => 'ticket-response-form']) !!}
        {!! Form::hidden('plan', json_encode($plan)) !!}
        {!! Form::hidden('ticket_resp_id', $plan['ticket_resp_id']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-10 col-md-8">
                        <i class="fa fa-commenting-o fa-3x header-icon"></i>
                        <h4>Respond - {!! $plan['description'] !!}</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')
                @include('pages/testplanner/partials/response_respond_details')

                @foreach($plan['tickets'] as $ticket)
                    <div class="row nested-block ticket-panel">
                        <legend>Ticket - {!! Html::link(isset($ticket['description_url']) ? $ticket['description_url'] : '#', $ticket['desc'], ['target' => '_blank', 'title' => 'Click to view issue in Jira']) !!}</legend>
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

                                {!! Form::textarea('notes_response', $notesResponse, ['class' => 'form-control notes-response', 'rows' => '10']) !!}
                            </div>
                        </div>
                        {!! Form::hidden('ticket_id', $ticket['id'], ['class' => 'ticket-id']) !!}
                    </div>
                @endforeach
            </div>
        </div>

        @include('pages/main/partials/double_submit_buttons', [
                'direction'     => 'pull-left',
                'class'		    => 'btn-custom',
                'updateBtnText' => 'Submit Response',
                'updateBtnId'	=> 'respond-btn',
                'backBtnText'   => 'Cancel',
                'backBtnId'		=> 'back-btn'
            ])

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            // Respond functionalities
            loadRespondJs();
        });

    </script>

@stop