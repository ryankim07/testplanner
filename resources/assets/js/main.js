
    /**
     * Test Planner dynamic ticket builder
     *
     * @param config
     * @returns {{load: load}}
     * @constructor
     */
    function TicketBuilder(config) {
        var config          = config;
        var formId          = '#' + config.formIdName;
        var ticketRowClass  = '.' + config.ticketRowName;
        var addBtnId        = '#' + config.addBtnName;
        var continueBtnId   = '#' + config.continueBtnName;
        var updateBtnId     = '#' + config.updateBtnName;
        var removeBtnClass  = '.' + config.removeBtnName;
        var clearBtnClass   = '.' + config.clearBtnName;
        var ticketRowClass  = '.' + config.ticketRowName;
        var ticketDescClass = '.' + config.ticketDescName;
        var objectiveClass  = '.' + config.objectiveName;
        var testStepsClass  = '.' + config.testStepsName;

        /**
         * Setup builder
         */
        function initiateBuilder()
        {
            // Set an ID for each ticket
            if(config.mode == 'build') {
                changeCreateTicketInputIndex($(ticketRowClass));
            }

            // If there is only one ticket, hide remove option
            if ($(ticketRowClass).length == 1) {
                $(removeBtnClass).hide();
            }

            // Add Ticket
            createTicket();

            // Remove Ticket
            removeTicket();

            // Continue or Update
            continueOrUpdate();

            // Jira Issues
            jiraIssues();
        }

        /**
         * Add ticket functionality
         */
        function createTicket()
        {
            $(formId).on('click', addBtnId, function() {
                // Clone first block
                var clonedRow = $(ticketRowClass).first().clone();

                // Clear all fields
                var inputTypes = clonedRow.find('input[type=text], textarea').val('');

                // Increment index
                changeCreateTicketInputIndex(clonedRow);

                // Add as new block after latest ticket row
                clonedRow.insertAfter($(ticketRowClass).last());

                // Display remove option
                $(removeBtnClass).show();
            });
        }

        /**
         * Remove ticket functionality
         */
        function removeTicket()
        {
            var addBtn = $(ticketRowClass + ' ' + addBtnId);

            $(formId).on('click', removeBtnClass, function(e) {
                e.preventDefault();

                $(this).closest(ticketRowClass).remove();

                // Cannot remove all the rows, only one should be left over
                if ($(ticketRowClass).length == 1) {
                    // The row that is left over, hide remove option
                    $(removeBtnClass).hide();

                    // Display back add ticket button
                    if (addBtn.css('display') == 'none') {
                        addBtn.show();
                    }
                }
            });
        }

        /**
         * Continue or Update
         */
        function continueOrUpdate()
        {
            $(formId).on('click', continueBtnId, updateBtnId, function() {
                var tickets = [];

                $(formId + ' ' + ticketRowClass).each(function() {
                    // Create ticket object
                    tickets.push({
                        "id": $(this).attr('id'),
                        "description": $(this).find(ticketDescClass).val(),
                        "objective": $(this).find(objectiveClass).val(),
                        "test_steps": $(this).find(testStepsClass).val()
                    });
                });

                // Create hidden field
                var input = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", config.ticketsObjName).val(JSON.stringify(tickets));

                $('form').append($(input));
            });
        }

        /**
         * Load Jira issues
         */
        function jiraIssues()
        {
            var jiraIssues = config.jira;

            $(formId).on('focus', ticketDescClass, function () {
                $(this).autocomplete({
                    source: jiraIssues
                });
            });

            $(formId).on('click', clearBtnClass, function () {
                $(config.ticketDescClass).val('');
            });
        }

        /**
         * Change input fields name to an array
         */
        function changeCreateTicketInputIndex(obj)
        {
            var index = stringGen(5);

            obj.attr('id', index);
            obj.find(ticketDescClass).attr('name', 'description["' + index + '"]');
            obj.find(objectiveClass).attr('name', 'objective["' + index + '"]');
            obj.find(testStepsClass).attr('name', 'test_steps["' + index + '"]');

            return true;
        }

        return {
            load: function() {
                initiateBuilder();
            }
        }
    }


    /**
     *
     * RESPONDING TO TICKET
     *
     */
    $('#respond-main').on('click', '#respond-btn', function() {
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
     *
     * VIEW RESPONSE DROPDOWN VIEWER FOR A CERTAIN USER
     *
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
     *
     * DASHBOARD
     *
     */
    $('#dashboard-main .admin_created_plans_rows').each(function() {
        var testerId = $(this).find('.testers option:nth-child(1)').val();
        var route = $(this).find('.testers').data('url') + '/' + testerId;
        var link = $(this).find('.plan-link').prop('href', route);
    });

// Change viewer id link
    $('#dashboard-main').on('change', '.testers', function() {
        var selectedTesterId = $(this).val();
        var route = $(this).data('url') + '/' + selectedTesterId;

        $(this).closest('td').next('td').find('.plan-link').prop('href', route);
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
     *
     * VIEW ALL ADMIN PLANS
     *
     */
        // View all admin
    $('#view-all-admin-main').on('click', '.view-tester-plan', function(e) {
        e.preventDefault();

        var parent = $(this).closest('tr');
        var tester = parent.find('.tester').val();
        var url    = $(this).attr('href');

        window.location.href = url + '/' + tester;
    });

    $('#view-all-assigned-main').on('click', '.toggler', function() {
        window.location.href = $(this).data('url');
    });


    /**
     *
     * USER ACCOUNTS
     *
     */
        // Display all user accounts
    $('#view-all-users-main').on('click', '.toggler', function(e) {
        e.preventDefault();

        var currentClass = $('#view-all-users-main').attr('class');

        if (currentClass != 'col-xs-12 col-md-8') {
            // Control width of both columns
            $('#view-all-users-main').toggleClass('col-md-12 col-md-8');
            $('#viewer-main').toggleClass('col-md-0 col-md-4');
        }

        // Selecting rows on mobile
        if (currentClass == 'col-xs-12 col-md-12') {
            $('#view-all-users-main').css({'z-index': '1000'});
        }

        $.when(
            $.ajax({
                method: "GET",
                url: $(this).data('url'),
                dataType: "json",
                success: function (resp) {
                    $('#viewer-main').html(resp.viewBody);
                }
            })
        ).done(function (resp) {
            // Close viewer
            $('.close-viewer').on('click', function (e) {
                e.preventDefault();
                $('#view-all-users-main').toggleClass('col-md-12 col-md-8');
                $('#viewer-main').toggleClass('col-md-0 col-md-4');
                $('#viewer-main').empty();
            });
        });
    });

    /**
     * Pre check all the radio buttons for browser testers
     * when editing form
     *
     * @param testers
     */
    function preSelectBrowserTesters(testers) {
        $('.browser-tester').each(function () {
            var browser = $(this);
            var browserId = browser.attr('id');

            $.each(testers, function (i, testerBrowserId) {
                if (browserId == testerBrowserId) {
                    browser.prop("checked", true);
                }
            });
        });
    }

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