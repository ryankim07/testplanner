{{--
|--------------------------------------------------------------------------
| Step 3
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="build-step-3-main">

        {!! Form::open(['route' => 'browser-tester.store', 'id' => 'tester-build-form']) !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Step 3 of 3 - Assign testers</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">75%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @include('pages/testplanner/partials/testers')

            </div>
        </div>

        @include('pages/main/partials/submit_button', [
            'submitBtnText' => 'Review',
            'direction'     => 'pull-right',
            'class'		    => 'btn-success btn-lg',
			'id'			=> 'continue-btn'
		])

        {!! Form::close() !!}

    </div>

@stop