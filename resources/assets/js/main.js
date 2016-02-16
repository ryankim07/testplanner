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
 * Dashboard
 */
function loadDashboardJs(url)
{
    // Disable all buttons for adding comment
    $('#dashboard-main .activity-comment-add').prop('disabled', true);


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
function loadRespondJs()
{
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

                    $.each(jqXhr.responseJSON, function(key, item) {
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

/**
 * Grab all selected browsers by user from the checkboxes
 *
 * @param formId
 */
function grabBrowserTesters(formId)
{
    $('#' + formId).on('click', '#continue-btn, #update-btn', function() {
        var browserTesters = [];

        $('.testers').each(function() {
            var id = $(this).data('id');
            var browsers = [];
            var inputIds = [];

            $(this).find('input[type="checkbox"]:checked').each(function () {
                inputIds.push('tester-' + id  + '-' + $(this).val());
                browsers.push($(this).val());
            });

            browserTesters.push({
                "id": id,
                "first_name": $(this).data('fname'),
                "email": $(this).data('email'),
                "browsers": browsers.join(','),
                "input-ids": inputIds
            });
        });

        var input = $("<input>").attr({"type":"hidden","name":"browser_testers"}).val(JSON.stringify(browserTesters));
        $('form').append(input);
    });
}

/**
 * Pre check all checkboxes for browser testers
 * when editing or add check icons when reviewing
 *
 * @param testers
 */
function preCheckBrowserTesters(testers, mode)
{
    var testers   = $.parseJSON(testers);
    var checkIcon = '<i class="fa fa-check"></i>';

    $.each(testers, function (i, objs) {
        var inputIds = mode == 'plan-edit' ? objs['browsers'] : objs['input-ids'];
        var responses = mode == 'plan-edit' ? objs['responses'] : '';

        if(mode == 'plan-edit') {
            inputIds = inputIds.split(',');
        }

        $.each(inputIds, function (i, browser) {
            if (mode == 'review') {
                $('#' + browser).replaceWith(checkIcon);
            } else {
                var radioEl = $('#' + browser);

                if (mode == 'plan-edit') {
                    inputId = 'tester-' + objs['user_id'] + '-' + browser;
                    radioEl = $('#' + inputId);
                }

                radioEl.prop('checked', true);

                if (mode == 'plan-edit') {
                    if (responses[browser] === true) {
                        radioEl.prop('disabled', true);
                    }
                }
            }
        });
    });

    if (mode == 'review') {
        $('input[type="checkbox"]').each(function () {
            $(this).replaceWith('');
        });
    }
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
            source: versions,
            select: function(event, ui) {
                $("#plan-description").val(ui.item.label);
                $("#jira-bvid").val(ui.item.value);

                return false;
            }
        });
    });

    clearInputField(formId, descId, '');
}

/**
 * Date range
 */
function planStartExpireDates()
{
    $('#started_at').datetimepicker({
        minDate: moment().add(1, 'h'),
        format: "MM/DD/YYYY"
    });
    $('#expired_at').datetimepicker({
        minDate: moment().add(1, 'h'),
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
 * General
 */
function backButtonSubmit(url) {
    $('form').on('click', '#back-btn', function() {
        window.location.href = url;
    });
}

/**
 * Activate tab
 *
 * @param formId
 * @param navClass
 * @param contentClass
 */
function activateTabNav(formId, navClass, contentClass)
{
    $('#' + formId + ' ' + '.' + navClass + ' li:first-child').addClass('active');
    $('#' + formId + ' ' + '.' + contentClass + ' div:first-child').removeClass('fade').addClass('active fade in');
}

/**
 * System
 */
function loadSystemJs()
{
    // Grab all the original contents
    var inputs = $('input[type="text"]').each(function() {
        $(this).data('original', this.value);
    });

    $('#system-main').on('click', '#update-btn', function() {
        var fields = [];
        var items  = {};

        // Clear existing flash messages
        if ($('.alert').length > 0) {
            $('.alert').remove();
        }

        // Grab token
        items['_token'] = $('form').find('input[name=_token]').val();

        // Grab only fields that were changed
        inputs.each(function() {
            if ($(this).data('original') != this.value) {
                items[$(this).data('name')] = this.value;
            }
        });

        // Update by Ajax
        $.when(
            $.ajax({
                method: "POST",
                url: $('form').attr('action'),
                data: items,
                dataType: "json",
                success: function (res) {
                    var divClass  = res.status == 'success' ? 'alert-success' : 'alert-danger';
                    var divIcon   = res.status == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                    var divSrOnly = res.status == 'success' ? 'Success' : 'Error';

                    var msgBlock = $('<div class="alert ' + divClass + '" role="alert"></div>');
                    msgBlock.empty().html('<i class="fa ' + divIcon + ' fa-lg" aria-hidden="true"></i><span class="sr-only">' + divSrOnly + '</span> ' + res.msg + '<br/>');

                    $('#system-main #system-main-panel-body').prepend(msgBlock);
                }
            })
        ).done(function() {
            var inputs = $('input[type="text"]').each(function() {
                $(this).data('original', this.value);
            });
        });

    });
}