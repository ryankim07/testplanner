{{--
|--------------------------------------------------------------------------
| Step 1 - Build or Edit plan
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

	<div class="col-xs-12 col-md-12 main plan-wizard" id="step-1-main">
        @if($mode == 'build')
		    {!! Form::open(['route' => 'plan.store', 'class' => 'form-horizontal', 'id' => 'plan-build-form']) !!}
        @else
			{!! Form::model($planData, ['method' => 'PATCH', 'action' => ['PlansController@update'], 'class' => 'form-horizontal', 'id' => 'plan-edit-form']) !!}
	    @endif
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-8">
						<i class="fa fa-cubes fa-3x header-icon"></i>
						<h4>Step 1 of 3 - {!! $mode == 'build' ? 'Start building plan' : 'Edit plan' !!}</h4>
					</div>
					@if($mode == 'build')
						<div class="col-md-4">
							<div class="progress">
								<div class="progress-bar progress-bar-user progress-bar-striped active" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100" style="width: 15%">15%</div>
							</div>
						</div>
					@endif
				</div>
			</div>
			<div class="panel-body">

				@include('errors.list')

				@if($mode == 'build')
				    {!! Form::hidden('creator_id', $userId) !!}
				@else
					{!! Form::hidden('creator_id', $planData['creator_id']) !!}
                @endif

				@include('pages/testplanner/partials/plan')

			</div>
		</div>

		@if($mode == 'build')
            @include('pages/main/partials/submit_button', [
                'submitBtnText' => 'Continue',
                'direction'     => 'pull-right',
                'class'		    => 'btn-primary',
                'id'			=> 'continue-btn'
            ])
        @else
			@include('pages/main/partials/double_submit_buttons', [
                'direction'     => 'pull-right',
                'class'		    => 'btn-primary',
                'updateBtnText' => 'Update',
                'updateBtnId'	=> 'update-btn',
                'backBtnText'   => 'Go Back',
                'backBtnId'		=> 'back-btn'
            ])
        @endif

		{!! Form::close() !!}

	</div>

	<script type="text/javascript">

		$(document).ready(function() {
			jiraVersions('step-1-main', 'plan-description', <?php echo $jira_versions; ?>);
			planStartExpireDates();
		});

	</script>

@stop