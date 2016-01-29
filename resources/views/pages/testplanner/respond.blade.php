{{--
|--------------------------------------------------------------------------
| Response | Respond template
|--------------------------------------------------------------------------
|
| This template is used when viewing, editing plan response.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="respond-main">

        {!! Form::open(['route' => 'ticket.save.response', 'class' => 'form-horizontal', 'id' => 'ticket-response-form']) !!}
        {!! Form::hidden('plan', json_encode(array_only($plan, ['id', 'tester_id', 'ticket_status']))) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-10 col-md-12">
                        <i class="fa fa-commenting-o fa-3x header-icon"></i>
                        <h4>Respond - {!! $plan['description'] !!}</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <ul class="nav nav-tabs">
                    {!! $tabHeaderHtml !!}
                </ul>
                <div class="tab-content">
                    {!! $tabBodyHtml !!}
                </div>
            </div>
        </div>

        @include('pages/main/partials/double_submit_buttons', [
            'direction'     => 'pull-right',
            'class'		   => 'btn-custom',
            'btnText'       => 'Cancel',
            'btnId'         => 'back-btn',
            'submitBtnText' => 'Submit Response',
            'submitBtnId'   => 'respond-btn'
        ])

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            // Activate first tab nav and tab content
            activateTabNav('respond-main', 'nav-tabs', 'tab-content');


            // Respond functionalities
            //loadResponseRespondJs();

            // Back button
            backButtonSubmit('{!! URL::previous() !!}');


            // If there are responded tickets, change button label for update
            var totalResponses = 0;

            $('#respond-main .ticket-panel').each(function() {
                var notesResponse = $(this).find('.notes-response');

                if (notesResponse.val() != '') {
                    totalResponses++;
                }
            });

            if (totalResponses > 0) {
                $('#respond-btn').prop('value', 'Update Response')
            } else if (totalResponses == 0) {
                $('#respond-btn').prop('disabled', true);

                $('#respond-main').on('focus', '.notes-response', function() {
                    $('#respond-btn').prop('disabled', false);
                });
            }

            $('#respond-main').on('click', '#respond-btn', function() {
                var browsers = {};
                var tickets  = [];

                $('.tab-pane').each(function() {
                    var browserId   = $(this).attr('id');
                    var ticketPanel = $(this).find('.ticket-panel');

                    ticketPanel.each(function() {
                        // Create ticket object
                        tickets.push({
                            "id":             $(this).attr('id'),
                            "test_status":    $(this).find('input[type="radio"]:checked').val(),
                            "notes_response": $(this).find('.notes-response').val()
                        });
                    });

                    browsers[browserId] = {
                        'ticket_resp_id': $(this).find('.ticket-resp-id').val(),
                        'ticket_status': $(this).find('.ticket-status').val(),
                        'tickets': tickets,
                    };

                    tickets = [];
                });

                // Create hidden field
                var input = $("<input>").attr({"type":"hidden","name":"tickets_obj"}).val(JSON.stringify(browsers));
                $('form').append(input);
            });
        });

    </script>

@stop