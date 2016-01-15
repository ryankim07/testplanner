{{--
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
|
| This template is used when showing dashboard page.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="dashboard-main">
        <div class="page-header">
            <h2>Dashboard</h2>
        </div>

        @include('errors.list')

        <!-- ASSIGNED OR ADMIN CREATED PLANS -->

        @if(!empty($plans))
            <div class="col-xs-12 col-md-7">
                @foreach($plans as $type => $plan)
                    <div class="row">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                @if($type == 'admin_created_plans')
                                    Plans assigned to others
                                @else
                                    Plans assigned to me
                                @endif
                            </div>
                            <div class="panel-body">
                                @if(count($plan) > 0)
                                    <div class="table-responsive">
                                        <table class="table dashboard-table">
                                            <thead>
                                            <tr>
                                                <th>Description</th>
                                                @if($type != 'admin_created_plans')
                                                    <th>Admin</th>
                                                @endif
                                                <th class="text-center">Status</th>
                                                <th>Created</th>
                                                @if($type == 'admin_created_plans')
                                                    <th>Testers</th>
                                                    <th class="text-center">View</th>
                                                @else
                                                    <th class="text-center">Respond</th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($plan as $attr)
                                                <tr class="{!! $type !!}_rows">
                                                    <td>{!! $attr['description'] !!}</td>
                                                    @if($type != 'admin_created_plans')
                                                        <td>{!! $attr['full_name'] !!}</td>
                                                    @endif
                                                    <td class="text-center">
                                                        @if($type == 'admin_created_plans')

                                                            <?php
                                                                if($attr['status'] == 'complete') {
                                                                    $label = 'label-default';
                                                                } else if($attr['status'] == 'progress') {
                                                                    $label = 'label-warning';
                                                                } else {
                                                                    $label = 'label-success';
                                                                }
                                                            ?>

                                                            <span class="label {!! $label !!}">{!! $attr['status'] !!}</span>
                                                        @else

                                                            <?php
                                                                if($attr['ticket_response_status'] == 'complete') {
                                                                    $trLabel = 'label-default';
                                                                } else if($attr['ticket_response_status'] == 'progress') {
                                                                    $trLabel = 'label-warning';
                                                                } else {
                                                                    $trLabel = 'label-success';
                                                                }
                                                            ?>

                                                            <span class="label {!! $trLabel !!}">{!! isset($attr['ticket_response_status']) ? $attr['ticket_response_status'] : 'new' !!}</span>
                                                        @endif
                                                    </td>
                                                    <td>{!! Utils::dateConverter($attr['created_at']) !!}</td>
                                                    @if($type == 'admin_created_plans')
                                                        <td>
                                                            {!! Form::select('testers', $attr['testers'], null, ['class' => 'form-control input-sm testers', 'data-url' => route('plan.view.response', $attr['id'])]) !!}
                                                        </td>
                                                        <td class="text-center"><a href="#" class="plan-link"><i class="fa fa-search fa-lg"></i></a></td>
                                                    @else
                                                        <td class="text-center"><a href="{!! URL::route('plan.respond', $attr['id']) !!}"><i class="fa fa-commenting-o fa-lg"></i></a></td>
                                                    @endif

                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <div class="pull-right">
                                            @if($type == 'admin_created_plans')
                                                {!! Html::linkRoute('plan.view.all.responses', 'View more') !!}
                                            @else
                                                {!! Html::linkRoute('plan.view.all.assigned', 'View more') !!}
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <p><span>No records found.</span></p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="col-xs-12 col-md-7">
                <div class="row">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <span>Plans</span>
                        </div>
                        <div class="panel-body">
                            <p><span>There are no plans at the current moment.</span></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- ACTIVITY STREAM -->

        <div class="col-xs-12 col-md-5">

            {!! Form::open(['route' => 'activity.comment.save', 'class' => 'form-horizontal', 'id' => 'activity-stream-form']) !!}

            <div class="panel panel-info">
                <div class="panel-heading">Activity Stream</div>
                <div class="panel-body">
                    @if(empty($activities))
                        <p><span>There are no activities at the current moment.</span></p>
                    @else
                        @foreach($activities as $stream)
                            <div class="row activity-stream nested-block">
                                <div class="col-xs-2 col-md-2"><img src="images/mophie-user.jpeg" alt="mophie-user" class="" width="40" height="40"></div>
                                <div class="col-xs-10 col-md-10">
                                    <div class="row">
                                        {!! $stream['activity'] !!}
                                    </div>
                                    <div class="row">
                                        <ul class="list-styled">
                                            @foreach($stream['comments'] as $eachComment)
                                                <li class="comment-line"><em>{!! $eachComment['comment'] !!} (comment by {!! $eachComment['commentator'] !!} on {!! $eachComment['created_at'] !!})</em></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="row">
                                        <ul class="list-inline">
                                            <li><i class="fa fa-clock-o fa-lg"></i> {!! $stream['created_at'] !!}</li>
                                            <li><i class="fa fa-commenting-o fa-lg"></i> <a href="#" class="activity-comment-link">Comment</a></li>
                                        </ul>
                                    </div>
                                    <div class="row activity-comment-content">
                                        <div class="form-group">
                                            <div class="col-xs-8 col-md-8">
                                                {!! Form::textarea('activity_comment', null, ['class' => 'form-control activity-comment', 'rows' => '4']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-8 col-md-8">
                                                {!! Form::button('Add', ['class' => 'btn btn-primary btn-sm activity-comment-add']) !!}
                                                {!! Form::button('Cancel', ['class' => 'btn btn-primary btn-sm activity-comment-cancel']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    {!! Form::hidden('as_id', $stream['id'], ['class' => 'as_id']) !!}

                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            // Dashboard functionalities
            loadDashboardJs('{!! URL::to('activity/save-comment') !!}');
        });

    </script>

@stop