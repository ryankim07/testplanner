{{--
|--------------------------------------------------------------------------
| Customer registration
|--------------------------------------------------------------------------
|
| This partial is used when showing customer registraton form.
|
--}}

@extends('layout.main.master')

@section('content')

<div class="content">
	<fieldset id="step2" class="form-group">
		<h5>Step 2 of 5</h5>
		<div class="row">
		    <div class="col-md-12">
		        <h6>All Fields Required</h6>
		    </div>
		</div>
          
        @include('errors.list')

        {!! Form::open(['route' => 'ticket.store', 'class' => '', 'id' => 'ticket-build-form']) !!}

        <div class="row ticket-row">
            <div class="col-md-12">
                <label for="description">Please enter the description for ticket</label>
                <button type="button" class="btn btn-primary btn-xs remove-ticket-btn">-</button>
                <input type="text" name="description" class="required form-control description">
                <label for="objective">Please enter the objective</label>
                <input type="text" name="objective" class="required form-control objective">
                <label for="test">Please enter the steps for test</label>
                <textarea name="test-steps" class="test-steps" rows="10" cols="80"></textarea>
                <div class="button-group">
                    <button type="button" class="btn btn-primary btn-xs add-ticket-btn">Add New Ticket</button>
                </div>
            </div>
        </div>

        @include('pages/main/partials/submit_button', ['submitBtnText' => 'Select Members'])

        {!! Form::close() !!}

    </fieldset>
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
            $('.btn-row').before(ticketRow);
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
                    "description": $(this).find('.description').val(),
                    "objective": $(this).find('.objective').val(),
                    "test_steps": $(this).find('.test-steps').val()
                });
            });

            // Create hidden field
            var input = $("<input>")
                .attr("type", "hidden")
                .attr("name", "tickets-obj").val(JSON.stringify(tickets));

            $('form').append($(input));
        });
    });

</script>

@stop