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
                                    Assigned to others
                                @else
                                    Assigned to me
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
                                                    <th>Creator</th>
                                                @endif
                                                <th>Status</th>
                                                <th>Created</th>
                                                @if($type == 'admin_created_plans')
                                                    <th>Testers</th>
                                                    <th>View</th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($plan as $detail)
                                                <tr>
                                                    <td>
                                                        @if($type == 'admin_created_plans')
                                                            {!! $detail['description'] !!}
                                                        @else
                                                            {!! Html::linkRoute('plan.respond', $detail['description'], [$detail['id']]) !!}
                                                        @endif
                                                    </td>
                                                    @if($type != 'admin_created_plans')
                                                        <td>{!! $detail['creator'] !!}</td>
                                                    @endif
                                                    <td>
                                                        @if($type == 'admin_created_plans')

                                                            <?php
                                                                if($detail['status'] == 'completed') {
                                                                    $label = 'label-default';
                                                                } else if($detail['status'] == 'pending') {
                                                                    $label = 'label-warning';
                                                                } else {
                                                                    $label = 'label-success';
                                                                }
                                                            ?>

                                                            <span class="label {!! $label !!}">{!! $detail['status'] !!}</span>
                                                        @else

                                                            <?php
                                                                if($detail['ticket_response_status'] == 'completed') {
                                                                    $trLabel = 'label-default';
                                                                } else if($detail['ticket_response_status'] == 'pending') {
                                                                    $trLabel = 'label-warning';
                                                                } else {
                                                                    $trLabel = 'label-success';
                                                                }
                                                            ?>

                                                            <span class="label {!! $trLabel !!}">{!! isset($detail['ticket_response_status']) ? $detail['ticket_response_status'] : 'new' !!}</span>
                                                        @endif
                                                    </td>
                                                    <td>{!! Utils::dateConverter($detail['created_at']) !!}</td>
                                                    @if($type == 'admin_created_plans')
                                                        <td>
                                                            @if($detail['status'] != 'completed')
                                                                {!! Form::select('testers', $detail['testers'], null, ['class' => 'form-control input-sm testers', 'data-url' => route('plan.view.response', $detail['id'])]) !!}
                                                            @endif
                                                        </td>
                                                        <td><a href="{!! URL::route('plan.view.response', [$detail['id'], $detail['user_id']]) !!}" class="view_tester_plan"><i class="fa fa-search fa-lg"></i></a></td>
                                                    @endif

                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        @if($type == 'admin_created_plans')
                                            {!! Html::linkRoute('dashboard.view.all.admin', 'View more') !!}
                                        @else
                                            {!! Html::linkRoute('dashboard.view.all.assigned', 'View more') !!}
                                        @endif
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

            {!! Form::open(['route' => 'dashboard.comment.save', 'class' => 'form-horizontal', 'id' => 'activity-stream-form']) !!}

            <div class="panel panel-info">
                <div class="panel-heading">Activity Stream</div>
                <div class="panel-body">
                    @if(!empty($activities))
                        @foreach($activities as $log)
                            <div class="row activity-log nested-block">
                                <div class="col-xs-2 col-md-2"><img src="images/mophie-user.jpeg" alt="mophie-user" class="thumbnail" width="40" height="40"></div>
                                <div class="col-xs-10 col-md-10">
                                    <div class="row">{!! $log['activity'] !!}</div>
                                    @foreach($log['comments'] as $eachComment)
                                        <div class="form-group">
                                            <div class="col-md-12 col-md-offset-0">
                                                <em>{!! $eachComment['comment'] !!} (comment by {!! $eachComment['commentator'] !!} on {!! $eachComment['created_at'] !!})</em>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row">
                                        <ul class="list-unstyled list-inline text-left">
                                            <li><i class="fa fa-clock-o fa-lg"></i> {!! $log['created_at'] !!}</li>
                                            <li><i class="fa fa-comment fa-lg"></i> <a href="#" class="activity-comment-link">Comment</a></li>
                                        </ul>
                                    </div>
                                    <div class="row activity-comment-content">
                                        <div class="form-group">
                                            <div class="col-xs-8 col-md-8">
                                                {!! Form::textarea('activity_comment', null, ['class' => 'form-control', 'rows' => '4']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-8 col-md-8">
                                                {!! Form::button('Add', ['class' => 'btn btn-primary btn-sm activity-comment-add']) !!}
                                                {!! Form::button('Cancel', ['class' => 'btn btn-primary btn-sm activity-comment-cancel']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    {!! Form::hidden('log_id', $log['id'], ['class' => 'log_id']) !!}

                                </div>
                            </div>
                        @endforeach
                    @else
                        <p><span>There are no activities at the current moment.</span></p>
                    @endif
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@stop