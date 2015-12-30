$(document).ready(function() {

    /**
     *  CREATING NEW TICKETS
     */
    // Clone first
    var ticketRow = $('.ticket-row').clone();

    // Since there is only one ticket, hide remove button
    $('.remove-ticket-btn').hide();

    // Append new ticket rows
    $(document).on('click', '#add-ticket-btn', function() {
        ticketRow.insertAfter('.nested-block').last();
        $('.remove-ticket-btn').show();
    });

    // Remove tickets
    $(document).on('click', '.remove-ticket-btn', function(e) {
        e.preventDefault();

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

    $('#submit-tickets-btn, #update-tickets-btn').on('click', function(e) {
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


    /**
     * RESPONDING TO TICKET
     */
    $('#respond-btn').on('click', function(e) {
        var tickets = [];

        $('.ticket-panel').each(function () {
            // Create ticket object
            tickets.push({
                "id": $(this).find('.ticket-id').val(),
                "test_status": $(this).find('input[type="radio"]:checked').val(),
                "notes_response": $(this).find('.notes-response').val()
            });
        });

        // Create hidden field
        var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "tickets-obj").val(JSON.stringify(tickets));

        $('form').append($(input));
    });


    /**
     * VIEW RESPONSE DROPDOWN VIEWER FOR A CERTAIN USER
     */

    $('#tester').on('change', function () {
        var route = "{!! URL::route('plan.view.response', null) !!}";
        var userId = $(this).val();
        var planId = $('#plan_id').val();

        if (userId != '') {
            window.location.href = route + '/' + planId + "/" + userId;
        }
    });


    /**
     * DASHBOARD ACTIVITY STREAM COMMENTS
     */
        // Hide initially
    $('.activity-comment-content').hide();

    // Toggle comment to show or hide
    $('.activity-comment-link').on('click', function (e) {
        e.preventDefault();
        var parent = $(this).parentsUntil('.activity-log');

        parent.find('.activity-comment-content').toggle();
    });

    // Add comment
    $('.activity-comment-add').on('click', function (e) {
        var parent  = $(this).parentsUntil('.activity-log');
        var logId   = parent.find('.log_id').val();
        var comment = parent.find('.activity-comment').val();

        $.ajax({
            method: "POST",
            url: "{!! URL::to('dashboard/comment') !!}",
            data: {
                "_token":  $('form').find('input[name=_token]').val(),
                "id":      logId,
                "comment": comment
            },
            dataType: "json"
        }).done(function(msg) {
            location.reload();
        });
    });

    // Cancel comment
    $('.activity-comment-cancel').on('click', function (e) {
        var parent = $(this).parentsUntil('.activity-log');
        parent.find('.activity-comment-content').hide();
    });


    /**
     * UTILITY FUNCTIONS
     */
    function stringGen(len)
    {
        var text = " ";

        var charset = "abcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < len; i++ )
            text += charset.charAt(Math.floor(Math.random() * charset.length));

        return text;
    }
});