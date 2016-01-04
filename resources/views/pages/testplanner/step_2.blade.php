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

                @include('pages/testplanner/partials/tickets', [
                    'mode'        => $mode,
                    'ticketsData' => $ticketsData
                ])

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
            /**
             *  CREATING NEW TICKETS
             */

            // Set an ID for each ticket
            var ticketRow = $('.ticket-row');

            @if($mode == 'build')
                // Increment index
                changeCreateTicketInputIndex(ticketRow);
            @endif

            // If there is only one ticket, hide remove option
            if ($('.ticket-row').length == 1) {
                $('.trash').hide();
            }

            // Append new ticket rows
            $('#step-2-main').on('click', '#add-ticket-btn', function() {
                // Clone first block
                var clonedRow = $('.ticket-row').first().clone();

                // Clear all fields
                var inputTypes = clonedRow.find('input[type=text], textarea').val('');

                // Increment index
                changeCreateTicketInputIndex(clonedRow);

                // Add as new block after latest ticket row
                clonedRow.insertAfter($('.ticket-row').last());

                // Display remove option
                $('.trash').show();
            });

            // Remove tickets
            $('#step-2-main').on('click', '.trash', function(e) {
                e.preventDefault();

                // Remove ticket row
                $(this).closest('.ticket-row').remove();

                // Cannot remove all the rows, only one should be left over
                if ($('.ticket-row').length == 1) {
                    // The row that is left over, hide remove option
                    $('.trash').hide();

                    // Display back add ticket button
                    if ($('.ticket-row .add-ticket-btn').css('display') == 'none') {
                        $('.ticket-row .add-ticket-btn').show();
                    }
                }
            });

            $('#step-2-main').on('click', '#continue-btn, #update-btn', function() {
                var tickets = [];

                $('#step-2-main .ticket-row').each(function() {
                    // Create ticket object
                    tickets.push({
                        "id": $(this).attr('id'),
                        "description": $(this).find('.ticket-description').val(),
                        "objective": $(this).find('.objective').val(),
                        "test_steps": $(this).find('.test-steps').val()
                    });
                });

                // Create hidden field
                var input = $("<input>")
                        .attr("type", "hidden")
                        .attr("name", "tickets_obj").val(JSON.stringify(tickets));

                $('form').append($(input));
            });

            var jiraIssues = <?php echo $jiraIssues; ?>

            $('#step-2-main').on('focus', '.ticket-description', function () {
                $(this).autocomplete({
                    source: jiraIssues
                });
            });

            $('#step-2-main').on('click', '.clear-btn', function () {
                $('.ticket-description').val('');
            });

            /**
             * String generator
             *
             * @param len
             * @returns {string}
             */
            function stringGen(len)
            {
                var text = "";

                var charset = "abcdefghijklmnopqrstuvwxyz0123456789";

                for( var i=0; i < len; i++ )
                    text += charset.charAt(Math.floor(Math.random() * charset.length));

                return text;
            }

            /**
             * Change certain input type names when creating new tickets
             *
             * @param obj
             * @param num
             * @returns {boolean}
             */
            function changeCreateTicketInputIndex(obj)
            {
                var index = stringGen(5);

                obj.attr('id', index);
                obj.find('.ticket-description').attr('name', 'description["' + index + '"]');
                obj.find('.objective').attr('name', 'objective["' + index + '"]');
                obj.find('.test-steps').attr('name', 'test_steps["' + index + '"]');

                return true;
            }
        });
    </script>

@stop