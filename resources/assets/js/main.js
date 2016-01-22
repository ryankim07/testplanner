/**
 * TEST PLANNER JS LIBRARY
 */

/**
 * General
 */

// Tooltip
$('.jira-issue').tooltip({
    container: 'body',
    placement: 'bottom'
});

// Pagination
$('.pagination').addClass('pagination-sm');


/**
 * View all admin plans
 */

// View or edit single plan
$('#view-all-created-main').on('click', '.edit-link', function() {
    window.location.href = $(this).data('url');
});

$('#view-all-created-main').on('change', '#admin', function() {
    var route = $(this).data('url');
    var adminId = $(this).val();

    $('form').attr('action', route + 'admin=' + adminId);
    $('form').submit();
});

// View all admin
$('#view-all-admin-main').on('click', '.view-tester-plan', function(e) {
    e.preventDefault();

    var parent = $(this).closest('tr');
    var tester = parent.find('.tester').val();
    var url    = $(this).attr('href');

    window.location.href = url + '/' + tester;
});

$('#view-all-assigned-main').on('click', '.edit-link', function() {
    window.location.href = $(this).data('url');
});

/**
 * Review build
 */
function buildReviewJs()
{
    if ($('.ticket-row').length == 1) {
        $('.trash').hide();
    }

    // Remove tickets
    $('#review-main').on('click', '.trash', function(e) {
        e.preventDefault();

        var ticketRow = $(this).closest('.ticket-row');
        var ticketId = $(this).data('id');

        // Ajax post
        $.when(
            $.ajax({
                method: "POST",
                url: "{!! URL::to('ticket/remove') !!}",
                data: {
                    "_token":  $('form').find('input[name=_token]').val(),
                    "ticketId": ticketId
                },
                success: function(resp) {
                    // Remove ticket row
                    if (resp == 'success') {
                        $('#' + ticketId).remove();
                    }
                }
            })
        ).done(function() {
        });

        // Cannot remove all the rows, only one should be left over
        if ($('.ticket-row').length == 1) {
            // The row that is left over, hide remove option
            $('.trash').hide();
        }
    });
}


/**
 * Dashboard
 */
function loadDashboardJs(url)
{
    // Hide initially
    $('#dashboard-main .activity-comment-area').hide();

    // Disable all buttons for adding comment
    $('#dashboard-main .activity-comment-add').prop('disabled', true);


    // Prepare link
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

    // Toggle comment to show or hide
    $('#dashboard-main').on('click', '.activity-comment-link', function(e) {
        e.preventDefault();
        var parent = $(this).parentsUntil('.activity-stream');

        parent.find('.activity-comment-area').toggle();
    });

    // Detect keypress on comment area and activate button
    $('#dashboard-main').on('focus, keypress', '.activity-comment', function() {
        var parent = $(this).parentsUntil('.activity-stream');
        parent.find('.activity-comment-add').prop('disabled', false);
    });

    // If comment is blank, deactivate button
    $('#dashboard-main').on('blur', '.activity-comment', function() {
        var parent = $(this).parentsUntil('.activity-stream');
        var comment = $(this).val();

        if (comment.length == 0) {
            parent.find('.activity-comment-add').prop('disabled', true);
        }
    });

    // Add comment
    $('#dashboard-main').on('click', '.activity-comment-add', function() {
        var parent  = $(this).parentsUntil('.activity-stream');
        var comment = parent.find('.activity-comment').val();

        // Ajax post
        $.when(
            $.ajax({
                method: "POST",
                url: url,
                data: {
                    "_token": $('form').find('input[name=_token]').val(),
                    "as_id": parent.find('.as_id').val(),
                    "comment": comment
                },
                dataType: "json",
                success: function (res) {
                    var lastCommentLine = parent.find($('.activity-comment-line').last());
                    var newCommentLine = $('<li class="activity-comment-line"><em>' + res.comment + ' (commented by ' + res.commentator + ' on ' + res.created_at + ')</em></li>');

                    // If this is a 1st comment, appending needs to take place right after ul
                    if (lastCommentLine.length == 0) {
                        $('.activity-comment-line-block ul').append(newCommentLine);
                    } else {
                        lastCommentLine.after(newCommentLine);
                    }
                },
                complete: function () {
                    // Clear comment textarea and hide block
                    parent.find('.activity-comment').val('');
                    parent.find('.activity-comment-area').hide();
                }
            })
        ).done(function() {
        });
    });

    // Cancel comment
    $('#dashboard-main').on('click', '.activity-comment-cancel', function() {
        var parent = $(this).parentsUntil('.activity-stream');
        parent.find('.activity-comment-area').hide();
    });
}


/**
 * Responding to ticket
 */
function loadResponseRespondJs()
{
    // If there are responded tickets, change button label for update
    var totalResponses = 0;

    $('#response-respond-main .ticket-panel').each(function() {
        var notesResponse = $(this).find('.notes-response');

        if (notesResponse.val() != '') {
            totalResponses++;
        }
    });

    if (totalResponses > 0) {
        $('#respond-btn').prop('value', 'Update Response')
    } else if (totalResponses == 0) {
        $('#respond-btn').prop('disabled', true);

        $('#response-respond-main').on('focus', '.notes-response', function() {
            $('#respond-btn').prop('disabled', false);
        });
    }

    $('#response-respond-main').on('click', '#respond-btn', function() {
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

    $('#response-respond-main').on('change', '#view-tester', function () {
        var route = $(this).data('url');
        var userId = $(this).val();
        var planId = $('#plan_id').val();

        if (userId != '') {
            window.location.href = route + '/' + planId + "/" + userId;
        }
    });
}


/**
 * User accounts
 */
function registerEditUserJs(mode, url)
{
    var newRoles = $("#role").val() || [];
    var formData = $('form').serialize() + '&role=' + newRoles
    var msgBlock = $('<div class="alert alert-danger" role="alert"></div>');

    // Clear existing flash messages
    if ($('.alert').length > 0) {
        $('.alert').remove();
    }

    // Ajax post
    $.when(
        $.ajax({
            method: "POST",
            url: url,
            data: formData,
            dataType: "json",
            success: function(resp) {
                var msgs = '';

                if (resp.type == 'success') {
                    window.location.href = resp.redirect_url;
                } else {
                    msgBlock.empty().html('<i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i><span class="sr-only">Error:</span> ' + resp.msg);
                    $('#user-main .panel-body').prepend(msgBlock);
                }
            },
            error: function(jqXhr) {
                // Messages only apply for request validation errors
                if(jqXhr.status === 422) {
                    var msgs = '';

                    $.each(jqXhr.responseJSON, function (key, item) {
                        msgs += '<i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i><span class="sr-only">Error:</span> ' + item + '<br/>';
                    });

                    msgBlock.empty().html(msgs);

                    $('#user-main .panel-body').prepend(msgBlock);
                }
            }
        })
    ).done(function() {
    });
}

function loadUsersJs(url, data)
{
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

    // Ajax post
    $.when(
        $.ajax({
            method: "GET",
            url: url,
            data: data,
            dataType: "json",
            success: function (resp) {
                $('#viewer-main').html(resp.viewBody);
            },
            complete: function() {
                // Close viewer
                $('.close-viewer').on('click', function (e) {
                    e.preventDefault();
                    $('#view-all-users-main').toggleClass('col-md-12 col-md-8');
                    $('#viewer-main').toggleClass('col-md-0 col-md-4');
                    $('#viewer-main').empty();
                });
            }
        })
    ).done(function() {
    });
}

function browserFieldGrabberJs()
{
    $('#step-3-main').on('click', '#continue-btn', function() {
        var browserTesters = [];
        $('.testers').each(function() {
            var browsers = [];

            $(this).find('input[type="checkbox"]:checked').each(function () {
                browsers.push($(this).val());
            });

            browserTesters.push({
                "id": $(this).data('id'),
                "first_name": $(this).data('fname'),
                "email": $(this).data('email'),
                "browsers": browsers.join(',')
            });
        });

        var input = $("<input>").attr({"type":"hidden","name":"browser_testers"}).val(JSON.stringify(browserTesters));
        $('form').append(input);
    });
}

/**
 * Pre check all the radio buttons for browser testers
 * when editing form
 *
 * @param testers
 */
function preSelectBrowserTesters(testers)
{
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
 * Clear input type field on button click
 *
 * @param formIdName
 * @param buttonClassName
 * @param inputTextIdName
 */
function clearInputField(formIdName, inputTextIdName, inputTextClassName)
{
    $('#' + formIdName).on('click', '.clear-btn', function () {
        if (inputTextIdName != '') {
            $('#' + inputTextIdName).val('');
        } else {
            $('.' + inputTextClassName).val('');
        }
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

/**
 * Load Jira issues
 */
function jiraIssues(formId, ticketDescClass, issues)
{
    $('#' + formId).on('focus', '.' + ticketDescClass, function () {
        $(this).autocomplete({
            source: issues
        });
    });

    $('#' + formId).on('click', '.clear-btn', function () {
        var parent = $(this).closest('.ticket-row');
        var ticketDesc = parent.find('.' + ticketDescClass);
        ticketDesc.val('');
    });
}

/**
 * Load Jira versions
 */
function jiraVersions(formId, descId, versions)
{
    $('#' + formId).on('focus', '#' + descId, function () {
        $(this).autocomplete({
            source: versions
        });
    });

    clearInputField(formId, descId, '');
}

/**
 * Date range
 */
function planStartExpireDates()
{
    var curDate = new Date();
    var yest    = curDate.setDate(curDate.getDate() - 1)

    $('#started_at').datetimepicker({
        minDate:yest,
        useCurrent: false,
        format: "MM/DD/YYYY"
    });
    $('#expired_at').datetimepicker({
        minDate:yest,
        useCurrent: false,
        format: "MM/DD/YYYY"
    });

    $("#started_at").on("dp.change", function (e) {
        $('#expired_at').data("DateTimePicker").minDate(e.date);
    });

    $("#expired_at").on("dp.change", function (e) {
        $('#started_at').data("DateTimePicker").maxDate(e.date);
    });
}

/**
 * Date range
 */
function planCreatedDates()
{
    $('#created_from').datetimepicker({
        useCurrent: false,
        format: "MM/DD/YYYY"
    });
    $('#created_to').datetimepicker({
        useCurrent: false,
        format: "MM/DD/YYYY"
    });
}

/**
 *
 * General
 *
 */
function backButtonSubmit(url) {
    $('form').on('click', '#back-btn', function() {
        window.location.href = url;
    });
}