{{--
|--------------------------------------------------------------------------
| Edit plan
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

	<div class="col-xs-12 col-md-12 main" id="view-main">

		{!! Form::model($plan, ['method' => 'PATCH', 'route' => ['plan.update.details', $plan['id']], 'class' => 'form-horizontal', 'id' => 'plan-edit-form']) !!}

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="clearfix">
					<div class="pull-left">
						<h4>Edit plan - {!! $plan['description'] !!}</h4>
					</div>
				</div>
			</div>
			<div class="panel-body">

				@include('errors.list')

				@include('pages/testplanner/partials/plan', [
					'description' => $plan['description'],
					'mode'        => 'edit'
				])

				<div class="page-header"></div>
					@foreach($plan['tickets'] as $ticket)
						@include('pages/testplanner/partials/tickets', [
							'ticket' => $ticket,
							'mode'   => 'edit'
						])
					@endforeach
				<div class="page-header"></div>

				<div class="row">
					<div class="col-md-12">
						{!! Form::label('testers', 'Testers') !!}
					</div>
				</div>

				@include('pages/testplanner/partials/testers', [
					'users' => $plan['testers'],
					'mode'  => 'edit'
				])

				@include('pages/main/partials/submit_button', [
					'submitBtnText' => 'Update',
					'css'           => 'col-xs-4 col-md-4',
					'id'			=> 'update-tickets-btn'
				])

				@include('pages/main/partials/back_link')
			</div>
		</div>

		{!! Form::close() !!}

	</div>

	<script type="text/javascript">

		<?php
			foreach($plan['testers'] as $tester) {
				$testers[] = 'browser_' . $tester['id'] . '_' . $tester['browser'];
			}

			echo 'var testers = ' . json_encode($testers);
		?>

	</script>

@stop