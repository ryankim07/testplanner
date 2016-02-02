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

    <div class="col-xs-12 col-md-12 main" id="response-main">

        {!! Form::open(['route' => 'plan.view.response', 'class' => 'form-horizontal', 'id' => 'plan-user-response-form']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-10 col-md-12">
                        <i class="fa fa-check-square-o fa-3x header-icon"></i>
                        <h4>Responses - {!! $description !!}</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <ul class="nav nav-pills nav-stacked col-xs-12 col-md-2">
                    {!! $usersTabHtml !!}
                </ul>
                <div class="tab-content col-xs-12 col-md-10">
                    {!! $browsersTabHtml !!}
                </div>
            </div>
        </div>

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            // Activate outer users first tab nav and tab content
            activateTabNav('response-main', 'nav-pills', 'tab-content');

            // Activate inner browsers first tab nav and tab content
            activateTabNav('response-main', 'nav-tabs', 'inner-tab');

            // Respond functionalities
            loadResponseRespondJs();

            // Back button
            backButtonSubmit('{!! URL::previous() !!}');
        });

    </script>

@stop