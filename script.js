
jQuery(document).ready(function($) {
    $('.contact-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var formMessage = $(this).find('.form-message');

        var ajaxUrl = ajax_object.ajax_url; 
        if (!ajaxUrl) {
            console.error('AJAX URL is not defined.'); 
            return;
        }

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: formData + '&action=handle_contact_form',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    formMessage.html('<p class="success-message">' + response.message + '</p>');
                    $('.contact-form')[0].reset(); // Reset the form with the class
                } else {
                    formMessage.html('<p class="error-message">' + response.message + '</p>');
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });
});