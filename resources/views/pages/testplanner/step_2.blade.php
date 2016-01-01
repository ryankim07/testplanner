{{--
|--------------------------------------------------------------------------
| Step 2 - Build or Edit tickets
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="step-2-main">
        @if($mode == 'build')
            {!! Form::open(['route' => 'ticket.store', 'id' => 'ticket-build-form']) !!}
        @else
            {!! Form::model($ticketsData, ['method' => 'PATCH', 'route' => ['ticket.update'], 'id' => 'ticket-edit-form']) !!}
        @endif
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Step 2 of 3 - {!! $mode == 'build' ? 'Add tickets to be tested' : 'Edit tickets to be tested' !!}</h4>
                    </div>
                    @if($mode == 'build')
                        <div class="col-md-4">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">45%</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @include('pages/testplanner/partials/tickets')

            </div>
        </div>

        @if($mode == 'build')
            @include('pages/main/partials/submit_button', [
                'submitBtnText' => 'Continue',
                'direction'     => 'pull-right',
                'class'		    => 'btn-success btn-lg',
                'id'			=> 'continue-btn'
            ])
        @else
            @include('pages/main/partials/update_back_button', [
                'direction'     => 'pull-right',
                'class'		    => 'btn-success btn-lg',
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
            var jiraIssues = <?php echo $jiraIssues; ?>

            $('#step-2-main').on('focus', '.ticket-description', function () {
                $(this).autocomplete({
                    source: jiraIssues
                });
            });

            $('#step-2-main').on('click', '.clear-btn', function () {
                $('.ticket-description').val('');
            });
        });
    </script>

@stop