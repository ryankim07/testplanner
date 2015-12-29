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

        <div class="col-xs-6 col-md-6">
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
                                                        <select name="testers">
                                                            <option value="" selected>Select One</option>

                                                            @foreach($detail['testers'] as $id => $firstName)
                                                                <option value="{!! $id !!}">{!! $firstName !!}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </td>
                                                <td><a href="{!! URL::route('plan.view.response', [$detail['id'], $detail['user_id']]) !!}" class="view_tester_plan"><span class="glyphicon glyphicon-search"></span></a></td>
                                            @endif

                                        </tr>

                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
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

    <div class="col-xs-6 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Activity Stream</div>
            <div class="panel-body">
                {!! Form::open(['route' => 'dashboard.comment.save', 'class' => 'enroll-form', 'id' => 'activity-stream-form']) !!}

                @foreach($activities as $log)

                    <div class="row activity_log">
                        <div class="col-xs-2 col-md-2"><img src="images/mophie-user.jpeg" alt="mophie-user" width="40" height="40"></div>
                        <div class="col-xs-10 col-md-10">
                            <div class="row">{!! $log['activity'] !!}</div>

                            @foreach($log['comments'] as $eachComment)

                                <div class="row">Comments: {!! $eachComment['comment'] !!} - added by {!! $eachComment['commentator'] !!}</div>

                            @endforeach

                            <div class="row">
                                <ul class="activity-actions">
                                    <li><span class="glyphicon glyphicon-time"></span> {!! $log['created_at'] !!}</li>
                                    <li class="activity-link-actions"><span class="glyphicon glyphicon-tag"></span> <a href="#" class="activity-comment-link">Comment</a></li>
                                </ul>
                            </div>
                            <div class="row activity_comment_content">
                                <textarea name="activity_comment" class="activity_comment" rows="4" cols="60"></textarea><br/>
                                <input type="button" name="add_comment" class="activity-comment-add" value="Add"> <input type="button" name="cancel_comment" class="activity-comment-cancel" value="Cancel">
                            </div>
                            <input type="hidden" name="log_id" class="log_id" value="{!! $log['id'] !!}">
                        </div>
                    </div>

                @endforeach

                {!! Form::close() !!}
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">

    $(document).ready(function() {
        // Hide initially
        $('.activity_comment_content').hide();

        // Toggle comment to show or hide
        $('.activity-comment-link').on('click', function (e) {
            e.preventDefault();
            var parent = $(this).parentsUntil('.activity_log');

            parent.find('.activity_comment_content').toggle();
        });

        // Add comment
        $('.activity-comment-add').on('click', function (e) {
            var parent  = $(this).parentsUntil('.activity_log');
            var logId   = parent.find('.log_id').val();
            var comment = parent.find('.activity_comment').val();

            $.ajax({
                method: "POST",
                url: "{!! URL::to('dashboard/comment') !!}",
                data: {
                    "_token":  $('form').find('input[name=_token]').val(),
                    "id":      logId,
                    "comment": comment
                },
                dataType: "json"
            }).done(function(msg) {
              location.reload();
            });
        });

        // Cancel comment
        $('.activity-comment-cancel').on('click', function (e) {
            var parent = $(this).parentsUntil('.activity_log');
            parent.find('.activity_comment_content').hide();
        });
    });

</script>

@stop