{{--
|--------------------------------------------------------------------------
| Step 3 - Build or edit testers
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="step-3-main">
        @if($mode == 'build')
            {!! Form::open(['route' => 'tester.store', 'id' => 'tester-build-form']) !!}
        @else
            {!! Form::model($testersData, ['method' => 'PATCH', 'route' => ['tester.update'], 'id' => 'tester-edit-form']) !!}
        @endif
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Step 3 of 3 - {!! $mode == 'build' ? 'Assign testers' : 'Edit testers' !!}</h4>
                    </div>
                    @if($mode == 'build')
                        <div class="col-md-4">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">75%</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @include('pages/testplanner/partials/testers')

            </div>
        </div>

        @if($mode == 'build')
            @include('pages/main/partials/submit_button', [
                'submitBtnText' => 'Continue',
                'direction'     => 'pull-right',
                'class'		    => 'btn-success btn-lg',
                'id'			=> 'continue-btn'
            ])
        @else
            @include('pages/main/partials/update_back_button', [
                'direction'     => 'pull-right',
                'class'		    => 'btn-success btn-lg',
                'updateBtnText' => 'Update',
                'updateBtnId'	=> 'update-btn',
                'backBtnText'   => 'Go Back',
                'backBtnId'		=> 'back-btn'
            ])
        @endif

        {!! Form::close() !!}

    </div>

@stop