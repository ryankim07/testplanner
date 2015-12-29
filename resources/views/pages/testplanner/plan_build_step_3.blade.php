{{--
|--------------------------------------------------------------------------
| Step 3
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12" id="main">

        {!! Form::open(['route' => 'browser-tester.store', 'class' => '', 'id' => 'tester-build-form']) !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left"><h3>Step 3 of 3 - Select browser testers</h3></div>
                    <div class="pull-right"><h5>All fields required</h5></div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @include('pages/testplanner/partials/testers')

                @include('pages/main/partials/submit_button', ['submitBtnText' => 'Review'])

            </div>
        </div>

        {!! Form::close() !!}

    </div>

@stop