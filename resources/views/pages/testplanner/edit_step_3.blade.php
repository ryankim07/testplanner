{{--
|--------------------------------------------------------------------------
| Step 3
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="build-step-3-main">

        {!! Form::open(['route' => 'browser-tester.store', 'id' => 'tester-build-form']) !!}
        {!! Form::model($planData, ['method' => 'PATCH', 'route' => ['plan.update', $plan['id']], 'id' => 'plan-edit-form']) !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Step 3 of 3 - Edit testers</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @include('pages/testplanner/partials/testers')

            </div>
        </div>

        @include('pages/main/partials/submit_button', [
            'submitBtnText' => 'Update',
            'direction'     => 'pull-right',
            'class'		    => 'btn-success btn-lg',
			'id'			=> 'continue-btn'
		])

        {!! Form::close() !!}

    </div>

@stop