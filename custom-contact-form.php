<?php
/*
Plugin Name: Custom Contact Form
Description: A simple contact form plugin.
Version: 1.0
Author: Kiranjit Kaur
*/

// Scripts and Styles
function custom_contact_form_scripts() {
    wp_enqueue_style('custom-contact-form-style', plugin_dir_url(__FILE__) . 'style.css');
    wp_enqueue_script('custom-contact-form-script', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), '1.0', true);
    wp_localize_script('custom-contact-form-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'custom_contact_form_scripts');

// Shortcode to display the contact form
function custom_contact_form_shortcode() {
    ob_start();
    ?>
    <div class="custom-contact-form">
        <form class="contact-form" method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <br>
            <input type="email" name="email" placeholder="Your Email" required>
            <br>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <br>
            <input type="submit" value="Submit">
        </form>
        <div class="form-message"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_contact_form', 'custom_contact_form_shortcode');

// Handle form submission
function handle_contact_form() {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);

        // Send email 
        $to = 'kiranjitkaur090@email.com'; 
        $subject = 'New Contact Form Submission';
        $headers = "From: $name <$email>";
        $body = "Name: $name\nEmail: $email\nMessage: $message";

        $sent = wp_mail($to, $subject, $body, $headers);

        if ($sent) {
            echo json_encode(array('status' => 'success', 'message' => 'Form submitted successfully!'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to submit form. Please try again.'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'All fields are required.'));
    }
    wp_die();
}
add_action('wp_ajax_handle_contact_form', 'handle_contact_form');
add_action('wp_ajax_nopriv_handle_contact_form', 'handle_contact_form');