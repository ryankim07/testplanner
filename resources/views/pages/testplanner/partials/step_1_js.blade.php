<script type="text/javascript">
    $(document).ready(function() {
        var versions = <?php echo $versions; ?>

            $('#build-step-1-main').on('focus', '#plan-description', function () {
            $(this).autocomplete({
                source: versions
            });
        });

        $('#build-step-1-main').on('click', '.clear-btn', function () {
            $('#plan-description').val('');
        });
    });
</script>