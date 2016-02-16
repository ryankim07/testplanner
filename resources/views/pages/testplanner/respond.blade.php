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

    <div class="col-xs-12 col-md-12 main" id="respond-main">

        {!! Form::open(['route' => 'ticket.save.response', 'class' => 'form-horizontal', 'id' => 'ticket-response-form']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-10 col-md-12">
                        <i class="fa fa-commenting-o fa-3x header-icon"></i>
                        <h4>Respond - {!! $plan['description'] !!}</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <ul class="nav nav-tabs">
                    {!! $tabHeaderHtml !!}
                </ul>
                <div class="tab-content">
                    {!! $tabBodyHtml !!}
                </div>
            </div>
        </div>

        @include('pages/main/partials/submit_and_button', [
            'direction'   => 'pull-right',
            'btnText'     => 'Cancel',
            'btnClass'    => 'btn-custom',
            'btnId'       => 'back-btn',
            'submitText'  => 'Submit Response',
            'submitClass' => 'btn-custom',
            'submitId'    => 'respond-btn'
        ])

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            // Activate first tab nav and tab content
            activateTabNav('respond-main', 'nav-tabs', 'tab-content');

            // Respond functionalities
            loadRespondJs();

            // Back button
            backButtonSubmit('{!! URL::previous() !!}');
        });

    </script>

@stop