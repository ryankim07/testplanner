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
	<fieldset class="form-group">
		<h5>Step 1 of 4</h5>
		<div class="row">
		    <div class="col-md-12">
		        <h6>All Fields Required</h6>
		    </div>
		</div>
          
        @include('errors.list')

        {!! Form::open(['route' => 'plan.store', 'class' => '', 'id' => 'plan-build-form']) !!}

		<div class="row">
			<div class="col-md-12">
				{!! Form::label('description', 'Please enter a description for this plan') !!}
				{!! Form::text('description', null, ['class' => 'required', 'id' => 'description']) !!}
				{!! Form::hidden('creator_id', $userId) !!}
			</div>
		</div>

        @include('pages/main/partials/submit_button', ['submitBtnText' => 'Add Tickets'])

        {!! Form::close() !!}

    </fieldset>
</div>

@stop