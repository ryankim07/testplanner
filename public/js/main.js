$(document).ready(function() {
    // Open viewer for dropdown
    $('.viewer').on('change, click', function(e) {
        e.preventDefault();

        var userId       = '';
        var currentClass = $('#main').attr('class');

        if (currentClass != 'col-xs-12 col-md-8') {
            // Control width of both columns
            $('#main').toggleClass('col-md-12 col-md-8');
            $('#viewer').toggleClass('col-md-0 col-md-4');
        }

        // Selecting rows on mobile
        if (currentClass == 'col-xs-12 col-md-12') {
            $('#main').css({'z-index':'1000'});
        }

        // Detect event type
        if (e.type == 'change') {
            userId = '/' + $(this).val();
        }

        $.when(
            $.ajax({
                method: "GET",
                url: $(this).data('url') + userId,
                dataType: "json",
                success: function(resp) {
                    $('#viewer').html(resp.viewBody);
                }
            })
        ).done(function(resp) {
            // Close viewer
            $('.close-viewer').on('click', function(e) {
                e.preventDefault();
                $('#main').toggleClass('col-md-12 col-md-8');
                $('#viewer').toggleClass('col-md-0 col-md-4');
                $('#viewer').empty();
            });
        });
    });

    // Redirect when clicking links from tables
    $('.link-viewer').on('click', function(e) {
        e.preventDefault();
        window.open($(this).attr('href'), '_blank');
    });

    $('.dashboard-toggler').on('click', function(e) {
        window.open($(this).data('url'), '_blank');
    });

    /*
     * Datepicker
     */
    // Purchased
    // Created
    $('#created_from').datetimepicker({
        useCurrent: false,
        format: "MM/DD/YYYY"
    });
    $('#created_to').datetimepicker({
        useCurrent: false,
        format: "MM/DD/YYYY"
    });

    $("#created_from").on("dp.change", function (e) {
        $('#created_to').data("DateTimePicker").minDate(e.date);
    });

    $("#created_to").on("dp.change", function (e) {
        $('#created_to').data("DateTimePicker").maxDate(e.date);
    });
});