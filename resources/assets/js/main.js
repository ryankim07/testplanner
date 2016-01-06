$(document).ready(function() {

    /**
     * RESPONDING TO TICKET
     */
    $('#respond-btn').on('click', function() {
        var tickets = [];

        $('.ticket-panel').each(function() {
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
            .attr("name", "tickets_obj").val(JSON.stringify(tickets));

        $('form').append($(input));
    });


    /**
     * VIEW RESPONSE DROPDOWN VIEWER FOR A CERTAIN USER
     */
    $('#view-response-main').on('change', '#view-tester', function() {
        var route  = $(this).data('url');
        var userId = $(this).val();
        var planId = $('#plan_id').val();

        if (userId != '') {
            window.location.href = route + '/' + planId + "/" + userId;
        }
    });


    /**
     * DASHBOARD
     */
    // Change viewer id link
    $('#dashboard-main').on('change', '.testers', function() {
        var selectedTesterId = $(this).val();
        var route = $(this).data('url') + '/' + selectedTesterId;

        $(this).closest('td').next('td').find('.view_tester_plan').prop('href', route);
    });

    // Hide initially
    $('.activity-comment-content').hide();

    // Toggle comment to show or hide
    $('#dashboard-main').on('click', '.activity-comment-link', function(e) {
        e.preventDefault();
        var parent = $(this).parentsUntil('.activity-log');

        parent.find('.activity-comment-content').toggle();
    });

    // Add comment
    $('#dashboard-main').on('click', '.activity-comment-add', function() {
        var parent  = $(this).parentsUntil('.activity-log');
        var logId   = parent.find('.log_id').val();
        var comment = parent.find('.activity-comment').val();

        $.ajax({
            method: "POST",
            url: "{!! URL::to('dashboard/save-comment') !!}",
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
    $('#dashboard-main').on('click', '.activity-comment-cancel', function() {
        var parent = $(this).parentsUntil('.activity-log');
        parent.find('.activity-comment-content').hide();
    });


    /**
     * ADMIN
     */

    // View all admin
    $('#view-all-admin-main').on('click', '.view-tester-plan', function(e) {
        e.preventDefault();

        var parent = $(this).closest('tr');
        var tester = parent.find('.tester').val();
        var url    = $(this).attr('href');

        window.location.href = url + '/' + tester;
    });

    // Show all or adminstrator plans
    $('#view-all-plans-main').on('change', '#view-user', function() {
        var route   = $(this).data('url');
        var adminId = $(this).val();

        window.location.href =  route + '/' + adminId;
    });

    // View or edit single plan
    $('#view-all-plans-main').on('click', '.toggler', function() {
        window.location.href = $(this).data('url');
    });


    /**
     * UTILITY FUNCTIONS
     */
});