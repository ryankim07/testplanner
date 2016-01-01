<script type="text/javascript">
    $(document).ready(function() {
        var jiraIssues = <?php echo $jiraIssues; ?>

            $('#build-step-2-main').on('focus', '.ticket-description', function () {
            $(this).autocomplete({
                source: jiraIssues
            });
        });

        $('#build-step-2-main').on('click', '.clear-btn', function () {
            $('.ticket-description').val('');
        });
    });
</script>