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
    <fieldset class="form-group">
        <h5>Step 4 of 4</h5>
        <div class="row">
            <div class="col-md-12">
                <h6>Please review your information and click submit to finish new test case:</h6>
            </div>
        </div>

        @include('errors.list')

        {!! Form::open(['action' => ['PlansController@save'], 'class' => '', 'id' => 'plan-review-form']) !!}

        <div class="row">
            <h5>Plan Description</h5>
            <ul class="list-unstyled">
                <li>{!! $plan['description'] !!}</li>
            </ul>
        </div>

        <div class="row">
            <h5>Tickets</h5>
            @foreach($tickets as $ticket)

                <ul class="list-unstyled">
                    <li>{!! $ticket['description'] !!}</li>
                    <li>{!! $ticket['objective'] !!}</li>
                    <li>{!! nl2br($ticket['test_steps']) !!}</li>
                </ul>

            @endforeach
        </div>

        <div class="row">
            <h5>Browser Testers</h5>
            <ul class="list-unstyled">

                @foreach($testers as $tester)

                    <li>{!! $tester['first_name'] !!} -> {!! $tester['browser'] !!}</li>

                @endforeach

            </ul>
        </div>

        <div class="row">
            {!! Form::submit('Create Test Plan', ['class' => 'green-btn step-btn', 'id' => 'submit-btn']) !!}
        </div>

        {!! Form::close() !!}

    </fieldset>
</div>

@stop