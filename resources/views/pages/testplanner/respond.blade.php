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

        @include('pages/main/partials/submit_and_button', [
            'direction'   => 'pull-right',
            'btnText'     => 'Cancel',
            'btnClass'    => 'btn-custom',
            'btnId'       => 'back-btn',
            'submitText'  => 'Submit Response',
            'submitClass' => 'btn-custom',
            'submitId'    => 'respond-btn'
        ])

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            // Activate first tab nav and tab content
            activateTabNav('respond-main', 'nav-tabs', 'tab-content');


            // Respond functionalities
            loadResponseRespondJs();

            // Back button
            backButtonSubmit('{!! URL::previous() !!}');

            // Grab older values
            var inputs = $('input[type="radio"], textarea').each(function() {
                $(this).data('original', this.value);
            });

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

            // If notes response is blank, deactivate button
            $('#respond-main').on('blur', '.notes-response', function() {
                var notes = $(this).val();

                if (notes.length == 0) {
                    $('#respond-btn').prop('disabled', true);
                }
            });

            $('#respond-main').on('click', '#respond-btn', function() {
                var browsers   = {};
                var tickets    = [];

                $('.tab-pane').each(function() {
                    var browserId   = $(this).attr('id');
                    var ticketPanel = $(this).find('.ticket-panel');

                    ticketPanel.each(function() {
                        var testStatus        = $(this).find('input[type="radio"]:checked').val();
                        var testStatusOrig    = $(this).find('input[type="radio"]').data('original');
                        var notesResponse     = $(this).find('.notes-response').val();
                        var notesResponseOrig = $(this).find('.notes-response').data('original');
                        var origData          = 'unmodified';

                        if (testStatus != testStatusOrig || notesResponse != notesResponseOrig) {
                            origData = 'modified';
                        }

                        // Create ticket object
                        tickets.push({
                            "id": $(this).attr('id'),
                            "test_status": testStatus,
                            "notes_response": notesResponse,
                            "original_data": origData
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