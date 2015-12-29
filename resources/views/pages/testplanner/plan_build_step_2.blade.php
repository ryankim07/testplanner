{{--
|--------------------------------------------------------------------------
| Step 2
|--------------------------------------------------------------------------
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12" id="main">

        {!! Form::open(['route' => 'ticket.store', 'class' => '', 'id' => 'ticket-build-form']) !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left"><h3>Step 2 of 3 - Add tickets to be tested</h3></div>
                    <div class="pull-right"><h5>All fields required</h5></div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                @include('pages/testplanner/partials/tickets', [
                    'ticket' => null,
                    'mode'   => 'create'
                ])

                @include('pages/main/partials/submit_button', ['submitBtnText' => 'Select Members'])

            </div>
        </div>

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            var ticketRow = $('.ticket-row').clone();

            /*
             *  TICKETS
             */
            // Since there is only one ticket, hide remove button
            $('.remove-ticket-btn').hide();

            // Append new ticket rows
            $(document).on('click', '.add-ticket-btn', function() {
                // Clone first
                $(this).hide();
                $('#continue-btn').before(ticketRow);
                $('.remove-ticket-btn').show();
            });

            // Remove tickets
            $(document).on('click', '.remove-ticket-btn', function() {
                $(this).closest('.ticket-row').remove();

                // Adjust button behavior whenever one element is visible
                if ($('.ticket-row').length == 1) {
                    // After removing tickets, the only one left cannot show
                    // remove button
                    $('.remove-ticket-btn').hide();

                    // Display back add ticket button
                    if ($('.ticket-row .add-ticket-btn').css('display') == 'none') {
                        $('.ticket-row .add-ticket-btn').show();
                    }
                }
            });

            /*
             * MAIN
             */

            $('#continue-btn').on('click', function(e) {
                var tickets = [];

                $('.ticket-row').each(function () {
                    // Create ticket object
                    tickets.push({
                        "id":          stringGen(5),
                        "description": $(this).find('.description').val(),
                        "objective":   $(this).find('.objective').val(),
                        "test_steps":  $(this).find('.test_steps').val()
                    });
                });

                // Create hidden field
                var input = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "tickets-obj").val(JSON.stringify(tickets));

                $('form').append($(input));
            });

            function stringGen(len)
            {
                var text = " ";

                var charset = "abcdefghijklmnopqrstuvwxyz0123456789";

                for( var i=0; i < len; i++ )
                    text += charset.charAt(Math.floor(Math.random() * charset.length));

                return text;
            }
        });

    </script>

@stop