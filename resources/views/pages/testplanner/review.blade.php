{{--
|--------------------------------------------------------------------------
| Regsitration review
|--------------------------------------------------------------------------
|
| This partial is used when showing registration review page.
|
--}}

@extends('layout.main.master')

@section('content')

<div class="content">
    <h2>Please review your information and click submit to finish new test case:</h2>

    {!! Form::open(['action' => ['PlansController@save'], 'class' => '', 'id' => 'plan-review-form']) !!}

    <div class="row">
        <div class="col-xs-6">
            <ul class="list-unstyled">
                <li><h4>Plan Description</h4></li>
                <li>{!! $plan['description'] !!}</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">

            @foreach($tickets as $ticket)

            <ul class="list-unstyled">
                <li>{!! $ticket['description'] !!}</li>
                <li>{!! $ticket['objective'] !!}</li>
                <li>{!! $ticket['test_steps'] !!}</li>
            </ul>

            @endforeach

        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <ul class="list-unstyled">
                <li>Browsers</li>

            @foreach($testers as $tester)

                <li>{!! $tester['first_name'] !!} -> {!! $tester['browser'] !!}</li>

            @endforeach

            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            {!! Form::submit('Create Test Plan', ['class' => 'green-btn step-btn', 'id' => 'submit-btn']) !!}
        </div>
    </div>

    {!! Form::close() !!}

</div>

@stop