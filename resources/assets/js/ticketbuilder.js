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
    }

    /**
     * Add ticket functionality
     */
    function createTicket()
    {
        $(formId).on('click', addBtnId, function() {
            // Clone first block
            var clonedField = $(ticketRowClass).first().clone();

            // Clear all fields
            var inputTypes = clonedField.find('input[type=text], textarea').val('');

            // Increment index
            changeCreateTicketInputIndex(clonedField);

            // Add as new block after latest ticket row
            clonedField.insertAfter($(ticketRowClass).last());

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
        var buttons = continueBtnId + ', ' + updateBtnId;
        $(formId).on('click', buttons, function() {
            var tickets = [];

            $(formId + ' ' + ticketRowClass).each(function() {
                // Create ticket object
                tickets.push({
                    "id": $(this).attr('id'),
                    "desc": $(this).find(ticketDescClass).val(),
                    "objective": $(this).find(objectiveClass).val(),
                    "test_steps": $(this).find(testStepsClass).val()
                });
            });

            // Create hidden field
            var input = $("<input>")
                .attr("type", "hidden")
                .attr("name", 'tickets_obj').val(JSON.stringify(tickets));

            $('form').append($(input));
        });
    }

    /**
     * Change input fields name to an array
     */
    function changeCreateTicketInputIndex(obj)
    {
        var index = stringGen(5);

        obj.attr('id', index);
        obj.find(ticketDescClass).attr('name', 'desc["' + index + '"]');
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
