// Export History Modal JavaScript Functions

// Initialize export history modal functionality
$(document).ready(function() {
    // Update form action based on selected form type
    $('#form-type').change(function() {
        var selectedValue = $(this).val();
        var form = $('#export-form');
        
        if (selectedValue) {
            form.attr('action', selectedValue);
        } else {
            form.attr('action', '');
        }
    });
});