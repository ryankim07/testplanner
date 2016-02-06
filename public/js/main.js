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
    // Hide initially
    $('#dashboard-main .activity-comment-area').hide();

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
function loadResponseRespondJs()
{

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

        if(mode == 'plan-edit') {
            inputIds = inputIds.split(',');
        }

        $.each(inputIds, function (i, inputId) {
            if (mode == 'review') {
                $('#' + inputId).replaceWith(checkIcon);
            } else {
                if (mode == 'plan-edit') {
                    inputId = 'tester-' + objs['user_id']  + '-' + inputId;
                }

                $('#' + inputId).prop('checked', true);
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
function jiraVersions(formId, descId, versions, renderUrl)
{
    $('#' + formId).on('focus', '#' + descId, function () {
        $(this).autocomplete({
            source: versions,
            select: function(event, ui) {
                $("#plan-description").val(ui.item.label);

                if (formId != 'view-main') {
                    $("#build-version-id").val(ui.item.value);
                } else {
                    renderTickets(ui.item.value, renderUrl);
                }

                return false;
            }
        });
    });

    clearInputField(formId, descId, '');
}

/**
 * Ajax render tickets dynamically
 *
 * @param buildVersionId
 * @param url
 */
function renderTickets(buildVersionId, url)
{
    // Ajax get newer issues
    $.when(
        $.ajax({
            method: "GET",
            url: url,
            data: {
                "_token": $('form').find('input[name=_token]').val(),
                "build_version_id": buildVersionId
            },
            dataType: "json",
            success: function(resp) {
                var allLatestTicket = $('form').find('.ticket-row');
                var latestTicket    = $('form').find('.ticket-row').last();
                var responses       = $(JSON.parse(resp));

                $.each(allLatestTicket, function() {
                    $(this).find('.trash').show();
                    $(this).css('background', 'red');
                });

                // Append unique IDs on each ticket block
                if (responses.length > 0) {
                    $.each(responses, function() {
                        var index = stringGen(5);

                        $(this).attr('id', index);
                        $(this).find('.ticket-description').attr('name', 'desc["' + index + '"]');
                        $(this).find('.objective').attr('name', 'objective["' + index + '"]');
                        $(this).find('.test-steps').attr('name', 'test_steps["' + index + '"]');
                    });
                }

                // Add new blocks after latest block
                latestTicket.after(responses);
            }
        })
    ).done(function() {
    });
}

/**
 * Date range
 */
function planStartExpireDates()
{
    var curDate = new Date();
    var yest    = curDate.setDate(curDate.getDate() - 1)

    $('#started_at').datetimepicker({
        useCurrent: false,
        format: "MM/DD/YYYY"
    });
    $('#expired_at').datetimepicker({
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

function activateTabNav(formId, navClass, contentClass)
{
    $('#' + formId + ' ' + '.' + navClass + ' li:first-child').addClass('active');
    $('#' + formId + ' ' + '.' + contentClass + ' div:first-child').removeClass('fade').addClass('active fade in');
}