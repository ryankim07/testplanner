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
            {!! Form::hidden('plan', json_encode($plan)) !!}
            {!! Form::hidden('ticket_resp_id', $plan['ticket_resp_id']) !!}
        @else
            {!! Form::open(['route' => 'plan.view.response', 'class' => 'form-horizontal', 'id' => 'plan-user-response-form']) !!}
        @endif

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-10 col-md-12">
                        <i class="fa {!! $mode == 'respond' ? 'fa-commenting-o' : 'fa-bug' !!} fa-3x header-icon"></i>
                        <h4>{!! ucfirst($mode)  !!} - {!! $plan['description'] !!}</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @if($mode == 'response' && count($totalResponses) == 0)
                    <p>Users, {!! config('testplanner.messages.plan.non_responses') !!}</p>
                @else
                    @if($mode == 'response')
                        <ul class="nav nav-tabs">
                            {!! $tabHeaderHtml !!}
                        </ul>
                        <div class="tab-content">
                            {!! $tabBodyHtml !!}
                        </div>
                    @else
                        {!! $planHtml !!}
                    @endif

                @endif
            </div>
        </div>

        @if($mode == 'respond')
            @include('pages/main/partials/double_submit_buttons', [
               'direction'     => 'pull-right',
               'class'		   => 'btn-custom',
               'updateBtnText' => 'Submit Response',
               'updateBtnId'   => 'respond-btn',
               'backBtnText'   => 'Cancel',
               'backBtnId'	   => 'back-btn'
            ])
        @endif

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            // Respond functionalities
            loadResponseRespondJs();

            // Back button
            backButtonSubmit('{!! URL::previous() !!}');
        });

    </script>

@stop