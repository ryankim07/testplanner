{{--
|--------------------------------------------------------------------------
| Response | Respond template
|--------------------------------------------------------------------------
|
| This template is used when viewing, editing plan response.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="response-respond-main">

        @if($mode == 'respond')
            {!! Form::open(['route' => 'ticket.save.response', 'class' => 'form-horizontal', 'id' => 'ticket-response-form']) !!}
        @else
            {!! Form::open(['route' => 'plan.view.response', 'class' => 'form-horizontal', 'id' => 'plan-user-response-form']) !!}
            {!! Form::hidden('plan_id', $plan['id'], ['id' => 'plan_id']) !!}
        @endif

            {!! Form::hidden('plan', json_encode($plan)) !!}
            {!! Form::hidden('ticket_resp_id', $plan['ticket_resp_id']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-10 col-md-8">
                        <i class="fa {!! $mode == 'respond' ? 'fa-commenting-o' : 'fa-comments' !!} fa-3x header-icon"></i>
                        <h4>{!! ucfirst($mode)  !!} - {!! $plan['description'] !!}</h4>
                    </div>
                    @if($mode == 'response')
                        <div class="col-xs-2 col-md-2 pull-right">
                            {!! Form::select('tester', $testers, null, ['class' => 'form-control input-sm', 'id' => 'view-tester', 'data-url' => route('plan.view.response', ['id' => null])]) !!}
                        </div>
                    @endif
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @if($mode == 'response' && empty($plan['ticket_resp_id']))
                    <p>{!! $plan['assignee'] !!}, {!! config('testplanner.plan_non_user_response_msg') !!}</p>
                @else
                    <div class="row nested-block">
                        <legend>Plan Details</legend>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <p>Admin: <strong>{!! $plan['reporter'] !!}</strong></p>
                                <p>Assignee: <strong>{!! $plan['assignee'] !!}</strong></p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <p>Started: <strong>{!! $plan['started_at'] !!}</strong></p>
                                <p>Expires: <strong>{!! $plan['expired_at'] !!}</strong></p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <p>Created: <strong>{!! $plan['created_at'] !!}</strong></p>
                                <p>Updated: <strong>{!! $plan['updated_at'] !!}</strong></p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
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

                                    <span class="label {!! $trLabel !!}">{!! empty($plan['ticket_status']) ? 'NEW' : strtoupper($plan['ticket_status']) !!}</span>
                                </p>
                                <p>Browser: {!! Html::image('images/' . $plan['browser'] . '.png', 'Browser', ['class' => 'browser-img']) !!}</p>
                            </div>
                        </div>
                    </div>
                    @foreach($plan['tickets'] as $ticket)
                        <div class="page-header"></div>
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
                                @if($mode == 'respond')
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
                                @else
                                    <?php
                                    $passed = '';
                                    $failed = '';
                                    if (isset($ticket['test_status'])) {
                                        $passed = $ticket['test_status'] == 1 ? true : '';
                                        $failed = $ticket['test_status'] == 0 ? true : '';
                                    }
                                    ?>

                                    <p>
                                        <span>
                                        @if($passed)
                                                Passed
                                            @elseif($failed)
                                                Failed
                                            @endif
                                        </span>
                                    </p>
                                @endif
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <legend>Notes</legend>
                                    @if($mode == 'respond')
                                        <?php $notesResponse = isset($ticket['notes_response']) ? $ticket['notes_response'] : null; ?>

                                        {!! Form::textarea('notes_response', $notesResponse, ['class' => 'form-control notes-response', 'rows' => '10']) !!}
                                    @else
                                        {!! isset($ticket['notes_response']) ? nl2br($ticket['notes_response']) : null !!}
                                    @endif
                                </div>
                            </div>
                        @if($mode == 'respond')
                            {!! Form::hidden('ticket_id', $ticket['id'], ['class' => 'ticket-id']) !!}
                        @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        @if($mode == 'respond')
            @include('pages/main/partials/double_submit_buttons', [
                'direction'     => 'pull-right',
                'class'		    => 'btn-custom',
                'updateBtnText' => 'Submit Response',
                'updateBtnId'	=> 'respond-btn',
                'backBtnText'   => 'Cancel',
                'backBtnId'		=> 'back-btn'
            ])
        @endif

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            // Respond functionalities
            loadResponseRespondJs();
        });

    </script>

@stop