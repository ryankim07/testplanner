{{--
|--------------------------------------------------------------------------
| Regsitration review
|--------------------------------------------------------------------------
|
| This partial is used when showing registration review page.
|
--}}

@extends('layout.main.master')
@section('body-class','enroll')

@section('content')

<div class="content">
    <h4>{!! $description !!}</h4>
    <h4>Please respond to each ticket:</h4>

    {!! Form::open(['route' => 'plan.save.user.response', 'class' => 'enroll-form', 'id' => 'plan-user-response-form']) !!}

    <input type="hidden" name="plan_id" value="{!! $id !!}">
    <input type="hidden" name="user_id" value="{!! $user_id !!}">

    @foreach($tickets as $ticket)

        <div class="row ticket-row">
            <div class="col-xs-6">
                <ul class="list-unstyled">
                    <li>Ticket: {!! $ticket['description'] !!}</li>
                    <li>Objective: {!! $ticket['objective'] !!}</li>
                    <li>
                        Steps to test:
                        <textarea>{!! $ticket['test_steps'] !!}</textarea>
                    </li>
                </ul>
            </div>
            <div class="col-xs-2">
                <input type="radio" name="status" value="1">
                <input type="radio" name="status" value="0">
            </div>
            <div class="col-xs-4">
                <label>Notes:</label>
                <textarea name="notes" class="notes" rows="10" cols="40"></textarea>
            </div>
            <input type="hidden" name="ticket_id" value="{!! $ticket['id'] !!}" class="ticket_id">
        </div>

    @endforeach

    <div class="row">
        <div class="col-xs-12">
            {!! Form::submit('Submit Response', ['class' => 'green-btn step-btn', 'id' => 'submit-btn']) !!}
        </div>
    </div>

    {!! Form::close() !!}

</div>

<script type="text/javascript">

    $(document).ready(function() {
        /*
         * MAIN
         */

        $('#submit-btn').on('click', function(e) {
            var tickets = [];

            $('.ticket-row').each(function () {
                // Create ticket object
                tickets.push({
                    "id": $(this).find('.ticket_id').val(),
                    "status": $(this).find('input[name=status]:checked').val(),
                    "notes": $(this).find('.notes').val()
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