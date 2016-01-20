{{--
|--------------------------------------------------------------------------
| Review new created plan
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="review-main">

        {!! Form::open(['route' => 'plan.save', 'class' => 'form-horizontal', 'id' => 'plan-review-form']) !!}

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 col-md-8">
                        <i class="fa fa-cubes fa-3x header-icon"></i>
                        <h4>Review</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="progress">
                            <div class="progress-bar progress-bar-primary progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Completed</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <div class="row nested-block">
                    <legend>Plan Details</legend>
                    <a href="{!! URL::route('plan.edit') !!}" class="pencil" title="Edit"><i class="fa fa-pencil fa-lg"></i></a>
                    <ul class="list-unstyled">
                        <li>Description: <strong>{!! $plan['description'] !!}</strong></li>
                        <li>Starts on: <strong>{!! $plan['started_at'] !!}</strong></li>
                        <li>Expires on: <strong>{!! $plan['expired_at'] !!}</strong></li>
                    </ul>
                </div>
                <div class="page-header"></div>
                @foreach($tickets as $ticket)
                    <div class="row nested-block ticket-row" id="{!! $ticket['id'] !!}">
                        <legend>Tickets</legend>
                        <a href="#" class="trash" data-id="{!! $ticket['id'] !!}" title="Delete"><i class="fa fa-trash-o fa-lg"></i></a>
                        <a href="{!! URL::route('ticket.edit') !!}" class="pencil" title="Edit"><i class="fa fa-pencil fa-lg"></i></a>
                        <ul class="list-unstyled">
                            <li><h4><span class="label label-default">Description</span></h4><h5>{!! $ticket['desc'] !!}</h5></li>
                            <li><h4><span class="label label-primary">Objective</span></h4><h5>{!! $ticket['objective'] !!}</h5></li>
                            <li><h4><span class="label label-info">Test Steps</span></h4><h5>{!! nl2br($ticket['test_steps']) !!}</h5></li>
                        </ul>
                    </div>
                @endforeach
                <div class="page-header"></div>
                <div class="row nested-block">
                    <legend>Browser Testers</legend>
                    <a href="{!! URL::route('tester.edit') !!}" class="pencil" title="Edit"><i class="fa fa-pencil fa-lg"></i></a>
                    @foreach($testers as $tester)
                        <div class="text-center review-testers">
                            {!! Html::image('images/' . $tester['browser'] . '.png', 'Browser', ['class' => 'browser-img']) !!}<h5><span class="caption">{!! $tester['first_name'] !!}</span></h5>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @include('pages/main/partials/submit_button', [
            'submitBtnText' => 'Finalize',
            'direction'     => 'pull-right',
            'class'		    => 'btn-primary',
            'id'            => 'continue-btn'
        ])

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            if ($('.ticket-row').length == 1) {
                $('.trash').hide();
            }

            // Remove tickets
            $('#review-main').on('click', '.trash', function(e) {
                e.preventDefault();

                var ticketRow = $(this).closest('.ticket-row');
                var ticketId = $(this).data('id');

                $.when(
                    $.ajax({
                        method: "POST",
                        url: "{!! URL::to('ticket/remove') !!}",
                        data: {
                            "_token":  $('form').find('input[name=_token]').val(),
                            "ticketId": ticketId
                        },
                        success: function(resp) {
                            // Remove ticket row
                            if (resp == 'success') {
                                $('#' + ticketId).remove();
                            }
                        }
                    })
                ).done(function(resp) {
                });

                // Cannot remove all the rows, only one should be left over
                if ($('.ticket-row').length == 1) {
                    // The row that is left over, hide remove option
                    $('.trash').hide();
                }
            });
        });

    </script>

@stop