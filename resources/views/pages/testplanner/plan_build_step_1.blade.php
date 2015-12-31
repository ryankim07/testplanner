{{--
|--------------------------------------------------------------------------
| Step 1
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

	<div class="col-xs-12 col-md-12 main" id="build-step-1-main">

		{!! Form::open(['route' => 'plan.store', 'class' => '', 'id' => 'plan-build-form']) !!}

		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-8">
						<h4>Step 1 of 3 - Start building plan</h4>
					</div>
                    <div class="col-md-4">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100" style="width: 15%">15%</div>
                        </div>
                    </div>
				</div>
			</div>
			<div class="panel-body">

				@include('errors.list')

				{!! Form::hidden('creator_id', $userId) !!}

				@include('pages/testplanner/partials/plan', [
					'userId'      => $userId,
					'description' => null
				])

			</div>
		</div>

		@include('pages/main/partials/submit_button', [
			'submitBtnText' => 'Continue',
            'direction'     => 'pull-right',
            'class'		    => 'btn-success btn-lg',
			'id'			=> 'continue-btn'
		])

		{!! Form::close() !!}

	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			var versions = <?php echo $versions; ?>

            $('#build-step-1-main').on('focus', '#plan-description', function () {
				$(this).autocomplete({
					source: versions
				});
			});

			$('#build-step-1-main').on('click', '.clear-btn', function () {
				$('#plan-description').val('');
			});
		});
	</script>


@stop