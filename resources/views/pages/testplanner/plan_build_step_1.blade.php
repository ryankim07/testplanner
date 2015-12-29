{{--
|--------------------------------------------------------------------------
| Step 1
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

	<div class="col-xs-12 col-md-12" id="main">

		{!! Form::open(['route' => 'plan.store', 'class' => '', 'id' => 'plan-build-form']) !!}

		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="clearfix">
					<div class="pull-left"><h3>Step 1 of 3 - Add plan details</h3></div>
					<div class="pull-right"><h5>All fields required</h5></div>
				</div>
			</div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::hidden('creator_id', $userId) !!}

				@include('pages/testplanner/partials/plan', [
					'userId'      => $userId,
					'description' => null,
					'mode'        => 'create'
				])

				@include('pages/main/partials/submit_button', ['submitBtnText' => 'Add Tickets'])
			</div>
		</div>

		{!! Form::close() !!}

	</div>

@stop