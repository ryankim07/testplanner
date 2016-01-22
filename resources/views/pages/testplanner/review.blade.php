{{--
|--------------------------------------------------------------------------
| Review new created plan
|--------------------------------------------------------------------------
--}}
<?php var_dump($testers['testers']) ?>
@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main plan-wizard" id="review-main">

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

                <div class="page-header"><h4>Plan Details</h4></div>
                <div class="row nested-block">
                    <a href="{!! URL::route('plan.edit') !!}" class="pencil" title="Edit"><i class="fa fa-pencil fa-lg"></i></a>
                    <ul class="list-unstyled">
                        <li>Description: <strong>{!! $plan['description'] !!}</strong></li>
                        <li>Starts on: <strong>{!! $plan['started_at'] !!}</strong></li>
                        <li>Expires on: <strong>{!! $plan['expired_at'] !!}</strong></li>
                    </ul>
                </div>
                <div class="page-header"><h4>Tickets</h4></div>
                @foreach($tickets as $ticket)
                    <div class="row nested-block ticket-row" id="{!! $ticket['id'] !!}">
                        <a href="#" class="trash" data-id="{!! $ticket['id'] !!}" title="Delete"><i class="fa fa-trash-o fa-lg"></i></a>
                        <a href="{!! URL::route('ticket.edit') !!}" class="pencil" title="Edit"><i class="fa fa-pencil fa-lg"></i></a>
                        <ul class="list-unstyled">
                            <li><h4><span class="label label-default">Description</span></h4><h5>{!! $ticket['desc'] !!}</h5></li>
                            <li><h4><span class="label label-primary">Objective</span></h4><h5>{!! $ticket['objective'] !!}</h5></li>
                            <li><h4><span class="label label-info">Test Steps</span></h4><h5>{!! nl2br($ticket['test_steps']) !!}</h5></li>
                        </ul>
                    </div>
                @endforeach
                <div class="page-header"><h4>Browser Testers</h4></div>
                <div class="row nested-block">
                    <a href="{!! URL::route('tester.edit') !!}" class="pencil" title="Edit"><i class="fa fa-pencil fa-lg"></i></a>
                    @include('pages/testplanner/partials/testers', ['testers' => $testers['users']])
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

            // Load ticket builder
            var ticketBuilder = new TicketBuilder({
                formIdName: 'review-main',
                ticketRowName: 'ticket-row',
                removeBtnName: 'trash'
            });

            // Remove ticket by Ajax
            ticketBuilder.removeAjax("{!! URL::to('ticket/remove') !!}");

            // Check all the selected browser testers
            preCheckBrowserTesters('<?php echo $testers['testers'] ?>');
        });

    </script>

@stop