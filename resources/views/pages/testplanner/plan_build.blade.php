{{--
|--------------------------------------------------------------------------
| Customer registration
|--------------------------------------------------------------------------
|
| This partial is used when showing customer registraton form.
|
--}}

@extends('layout.main.master')

@section('content')

<div class="content">
	<fieldset id="step2" class="form-group">
		<h5>Step 1 of 5</h5>
		<div class="row">
		    <div class="col-md-12">
		        <h6>All Fields Required</h6>
		    </div>
		</div>
          
        @include('errors.list')

        {!! Form::open(['route' => 'plan.store', 'class' => '', 'id' => 'plan-build-form']) !!}

        @include('pages/testplanner/partials/plan', ['userId' => $userId])
        @include('pages/main/partials/submit_button', ['submitBtnText' => 'Add Tickets'])

        {!! Form::close() !!}

    </fieldset>
</div>

@stop