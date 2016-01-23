{{--
|--------------------------------------------------------------------------
| Step 3 - Build or edit testers
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main plan-wizard" id="step-3-main">
        @if($mode == 'build')
            {!! Form::open(['route' => 'tester.store', 'class' => 'form-horizontal', 'id' => 'tester-build-form']) !!}
        @else
            {!! Form::model($testers, ['method' => 'PATCH', 'action' => ['TestersController@update'], 'class' => 'form-horizontal', 'id' => 'tester-edit-form']) !!}
        @endif
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 col-md-8">
                        <i class="fa fa-cubes fa-3x header-icon"></i>
                        <h4>Step 3 of 3 - {!! $mode == 'build' ? 'Assign testers' : 'Edit browser testers' !!}</h4>
                    </div>
                    @if($mode == 'build')
                        <div class="col-md-4">
                            <div class="progress">
                                <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">75%</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <div class="page-header"><h4>Browsers</h4></div>
                <div class="row nested-block">
                    @include('pages/testplanner/partials/testers')
                </div>
            </div>
        </div>

        @if($mode == 'build')
            @include('pages/main/partials/submit_button', [
                'submitBtnText' => 'Continue',
                'direction'     => 'pull-right',
                'class'		    => 'btn-primary',
                'id'			=> 'continue-btn'
            ])
        @else
            @include('pages/main/partials/double_submit_buttons', [
                'direction'     => 'pull-right',
                'class'		    => 'btn-primary',
                'btnText'       => 'Go Back',
                'btnId'		    => 'back-btn',
                'submitBtnText' => 'Update',
                'submitBtnId'	=> 'update-btn',
            ])
        @endif

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            @if($mode == 'edit')
                preCheckBrowserTesters('<?php echo $testers ?>', '{!! $mode !!}');
            @endif

            grabBrowserTesters();
        });

    </script>

@stop