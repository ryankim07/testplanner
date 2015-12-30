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

<div class="col-xs-12 col-md-12" id="main">
    <div class="page-header">
        <h2>Dashboard</h2>
    </div>

    @include('errors.list')

    @if (count($plans) > 0)

        <div class="col-xs-12 col-md-6">
            @foreach($plans as $type => $plan)

                <div class="row">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            @if($type == 'admin_created_plans')
                                Assigned to others
                            @else
                                Assigned to me
                            @endif
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table dashboard">
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

                                                    <span class="label {!! $trLabel !!}">{!! $detail['ticket_response_status'] !!}</span>
                                                @endif
                                            </td>
                                            <td>{!! Utils::dateConverter($detail['created_at']) !!}</td>

                                            @if($type == 'admin_created_plans')
                                                <td>
                                                    @if ($detail['status'] != 'completed')
                                                        {!! Form::select('testers', $detail['testers'], null, ['class' => 'form-control input-sm tester']) !!}
                                                    @endif
                                                </td>
                                                <td><a href="{!! URL::route('plan.view.response', [$detail['id'], $detail['user_id']]) !!}" class="view_tester_plan"><span class="glyphicon glyphicon-search"></span></a></td>
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
                        </div>
                    </div>
                </div>

            @endforeach

        </div>

    @endif

    <div class="col-xs-12 col-md-6">

        {!! Form::open(['route' => 'dashboard.comment.save', 'class' => 'enroll-form', 'id' => 'activity-stream-form']) !!}

        <div class="panel panel-primary">
            <div class="panel-heading">Activity Stream</div>
            <div class="panel-body">

                @foreach($activities as $log)

                    <div class="row activity-log nested-block">
                        <div class="col-xs-2 col-md-2"><img src="images/mophie-user.jpeg" alt="mophie-user" width="40" height="40"></div>
                        <div class="col-xs-10 col-md-10">
                            <div class="form-group">{!! $log['activity'] !!}</div>

                            @foreach($log['comments'] as $eachComment)

                                <div class="form-group">
                                    <div class="col-md-12 col-md-offset-0">
                                        <em>{!! $eachComment['comment'] !!} (comment by {!! $eachComment['commentator'] !!} on {!! $eachComment['created_at'] !!})</em>
                                    </div>
                                </div>

                            @endforeach

                            <div class="form-group">
                                <ul class="activity-actions">
                                    <li><span class="glyphicon glyphicon-time"></span> {!! $log['created_at'] !!}</li>
                                    <li class="activity-link-actions"><span class="glyphicon glyphicon-tag"></span> <a href="#" class="activity-comment-link">Comment</a></li>
                                </ul>
                            </div>
                            <div class="form-group activity-comment-content">
                                {!! Form::textarea('activity_comment', null, ['class' => 'form-control', 'rows' => '4']) !!}
                                <div class="comment-btn">
                                    <input type="button" name="add_comment" class="btn btn-primary btn-sm activity-comment-add" value="Add"> <input type="button" name="cancel_comment" class="btn btn-primary btn-sm activity-comment-cancel" value="Cancel">
                                </div>
                            </div>

                            {!! Form::hidden('log_id', $log['id'], ['class' => 'log_id']) !!}

                        </div>
                    </div>

                @endforeach

            </div>
        </div>

        {!! Form::close() !!}

    </div>

</div>

@stop