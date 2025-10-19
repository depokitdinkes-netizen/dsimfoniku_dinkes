/**
 * Perpipaan INT012 Select Handler
 * Handles the "Other" option for water source selection
 */

$(document).ready(function() {
    $('#int012-select').change(function() {
        $('#int012').val(this.value);

        if (this.value == 'Other') {
            $('#int012').removeClass('hidden');
            $('#int012').val('');
        } else {
            $('#int012').addClass('hidden');
        }
    })
});
