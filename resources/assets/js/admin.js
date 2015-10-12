$(document).ready(function() {
    // Open viewer if closed, otherwise do nothing
    $('.toggler').on('click', function(e) {
        e.preventDefault();

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

        $.when(
            $.ajax({
                method: "GET",
                url: $(this).data('url'),
                dataType: "json",
                success: function(resp) {
                    $('#viewer').html(resp.viewBody);
                    $('.modal-win .modal-title').html(resp.editTitle);
                    $('.modal-win .modal-body').html(resp.editBody);
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

                // Open modal window
                $('.modal-link').on('click', function(e) {
                    e.preventDefault();
                    $('.modal-win').modal('show');
                });

                // Update contents in modal window
                $('.modal-win-update').on('click', function(e) {
                    $('.admin-form-update').submit();
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