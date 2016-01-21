{{--
|--------------------------------------------------------------------------
| Edit plan
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

	<div class="col-xs-12 col-md-12 main" id="view-main">

		{!! Form::model($plan, ['method' => 'PATCH', 'action' => ['PlansController@updateBuiltPlan', $plan['id']], 'class' => 'form-horizontal', 'id' => 'plan-edit-form']) !!}

		<div class="panel panel-default">
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

				@include('pages/testplanner/partials/plan', ['mode' => 'edit'])

				{!! $plan['tickets_html'] !!}

				@include('pages/testplanner/partials/testers', [
					'testers' => $plan['users'],
					'mode'    => 'edit'
				])

			</div>
		</div>

		@include('pages/main/partials/double_submit_buttons', [
			'direction'     => 'pull-right',
            'class'		    => 'btn-custom',
            'btnText'       => 'Cancel',
            'btnId'		    => 'back-btn',
            'submitBtnText' => 'Update',
            'submitBtnId'	=> 'update-btn',
        ])

		{!! Form::close() !!}

	</div>

	<script type="text/javascript">

		$(document).ready(function() {
			// Load Jira versions, issues
			jiraVersions('view-main', 'plan-description', <?php echo $plan['jira_versions']; ?>);
			jiraIssues('view-main', 'ticket-description', <?php echo $plan['jira_issues']; ?>);

			// Fill expiration date
			planStartExpireDates();

			// Create new tickets
			var ticketBuilder = new TicketBuilder({
				mode: 'edit',
				formIdName: 'view-main',
				ticketRowName: 'ticket-row',
				ticketDescName: 'ticket-description',
				objectiveName: 'objective',
				testStepsName: 'test-steps',
				ticketsObjName: 'tickets_obj',
				addBtnName: 'add-ticket-btn',
				removeBtnName: 'trash',
				continueBtnName:'continue-btn',
				updateBtnName: 'update-btn'
			});

			// Load ticket builder
			ticketBuilder.load();

			// Preselect testers radion input
			preSelectBrowserTesters(<?php echo $plan['testers']; ?>);

			// Back button
			backButtonSubmit('{!! URL::previous() !!}');
		});

	</script>
@stop