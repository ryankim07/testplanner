{{--
|--------------------------------------------------------------------------
| Edit plan
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

	<div class="col-xs-12 col-md-12 main" id="view-main">

		{!! Form::model($plan, ['method' => 'PATCH', 'action' => ['PlansController@updateBuiltPlan', $plan['id']], 'class' => 'form-horizontal', 'id' => 'plan-edit-form']) !!}

		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-10 col-md-10">
						<i class="fa fa-pencil-square-o fa-3x header-icon"></i>
						<h4>Edit plan - {!! $plan['description'] !!}</h4>
					</div>
				</div>
			</div>
			<div class="panel-body">

				@include('errors.list')

				@include('pages/testplanner/partials/plan', [
					'mode' => 'edit'
				])

				<div class="page-header"></div>
					{!! $plan['tickets_html'] !!}
				<div class="page-header"></div>

				@include('pages/testplanner/partials/testers', [
					'testers' => $plan['testers'],
					'mode'    => 'edit'
				])

			</div>
		</div>

		@include('pages/main/partials/submit_button', [
            'submitBtnText' => 'Update',
            'css'           => 'col-xs-4 col-md-4',
            'id'			=> 'update-tickets-btn'
        ])

		{!! Form::close() !!}

	</div>

	<script type="text/javascript">

		$(document).ready(function() {
			<?php
				foreach($plan['testers'] as $tester) {
					$testers[] = 'tester-' . $tester['id'] . '-' . $tester['browser'];
				}

				echo 'var testers = ' . json_encode($testers);
			?>

			preSelectBrowserTesters(testers);
		});

	</script>
@stop