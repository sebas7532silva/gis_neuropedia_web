<?php
/**
 * Handle mail requests
 */

if (!function_exists('pearl_send_message')) {
    /**
     * contact form handler
     */
    function pearl_send_message()
    {

        if (isset($_POST['email'])):

            $nonce = $_POST['nonce'];

            if (!wp_verify_nonce($nonce, 'send_message_nonce')) {
                echo json_encode(array(
                    'success' => false,
                    'message' => esc_html__('Unverified Nonce!', 'pearl-medical-framework')
                ));
                die;
            }

            $to_email = sanitize_email($_POST['target']);
            $to_email = is_email($to_email);
            if (!$to_email) {
                echo wp_json_encode(array(
                    'success' => false,
                    'message' => esc_html__('Target Email address is not properly configured!', 'pearl-medical-framework')
                ));
                die;
            }

            /*
             *  Sanitize and Validate contact form input data
             */
            $from_name = sanitize_text_field($_POST['name']);
            $phone_number = sanitize_text_field($_POST['number']);
            $message = stripslashes($_POST['message']);
            $from_email = sanitize_email($_POST['email']);
            $from_email = is_email($from_email);
            if (!$from_email) {
                echo json_encode(array(
                    'success' => false,
                    'message' => esc_html__('Provided Email address is invalid!', 'pearl-medical-framework')
                ));
                die;
            }

            $email_subject = esc_html__('New message sent by', 'pearl-medical-framework') . ' ' . $from_name . ' ' . esc_html__('using contact form at', 'pearl-medical-framework') . ' ' . get_bloginfo('name');
            $email_body = esc_html__("You have received a message from: ", 'pearl-medical-framework') . $from_name . " <br/>";

            if (!empty($phone_number)) {
                $email_body .= esc_html__("Phone Number : ", 'pearl-medical-framework') . $phone_number . " <br/>";
            }

	        $email_body .= esc_html__( "Their additional message is as follows.", 'pearl-medical-framework' ) . " <br/>";
	        $email_body .= wpautop( $message ) . " <br/>";
	        $email_body .= esc_html__( "You can contact ", 'pearl-medical-framework' ) . $from_name . esc_html__( " via email, ", 'pearl-medical-framework' ) . $from_email;

	        $header = 'Content-type: text/html; charset=utf-8' . "\r\n";
	        $header .= 'From: ' . $from_name . " <" . $from_email . "> \r\n";

            if (isset($_POST['cc_email']) || isset($_POST['bcc_email'])) {

                if (isset($_POST['cc_email'])) {
                    $cc_email  = sanitize_email( $_POST['cc_email'] );
                    if( ! empty( $cc_email ) ) {
                        $header .= 'Cc: ' . $cc_email . "\r\n";
                    }
                }

                if (isset($_POST['bcc_email'])) {
                    $bcc_email = sanitize_email( $_POST['bcc_email'] );
                    if( ! empty( $bcc_email ) ) {
                        $header .= 'Bcc: ' . $bcc_email . "\r\n";
                    }
                }
            }

	        $header = apply_filters( "pearl_contact_mail_header", $header );

            if (wp_mail($to_email, $email_subject, $email_body, $header)) {
                echo json_encode(array(
                    'success' => true,
                    'message' => esc_html__("Message Sent Successfully!", 'pearl-medical-framework')
                ));
            } else {
                echo json_encode(array(
                        'success' => false,
                        'message' => esc_html__("Server Error: WordPress mail function failed!", 'pearl-medical-framework')
                    )
                );
            }

        else:
            echo json_encode(array(
                    'success' => false,
                    'message' => esc_html__("Invalid Request !", 'pearl-medical-framework')
                )
            );
        endif;

        die;
    }

    add_action('wp_ajax_nopriv_pearl_send_message', 'pearl_send_message');
    add_action('wp_ajax_pearl_send_message', 'pearl_send_message');

}

if (!function_exists('pearl_appointment_request')) {
    /**
     * contact form handler
     */
    function pearl_appointment_request()
    {

        if (isset($_POST['email'])):

            $nonce = $_POST['nonce'];

            if (!wp_verify_nonce($nonce, 'appointment_request_nonce')) {
                echo json_encode(array(
                    'success' => false,
                    'message' => esc_html__('Unverified Nonce!', 'pearl-medical-framework')
                ));
                die;
            }

            $to_email = sanitize_email($_POST['target']);
            $to_email = is_email($to_email);
            if (!$to_email) {
                echo wp_json_encode(array(
                    'success' => false,
                    'message' => esc_html__('Target Email address is not properly configured!', 'pearl-medical-framework')
                ));
                die;
            }

            /*
             *  Sanitize and Validate contact form input data
             */
            $from_name = sanitize_text_field($_POST['name']);
            $phone_number = sanitize_text_field($_POST['number']);
            $app_date = sanitize_text_field($_POST['app_date']);
            $message = stripslashes($_POST['message']);
            $from_email = sanitize_email($_POST['email']);
            $from_email = is_email($from_email);
            if (!$from_email) {
                echo json_encode(array(
                    'success' => false,
                    'message' => esc_html__('Provided Email address is invalid!', 'pearl-medical-framework')
                ));
                die;
            }

            $email_subject = esc_html__('Appointment request sent by', 'pearl-medical-framework') . ' ' . $from_name . ' ' . esc_html__('using Appointment form at', 'pearl-medical-framework') . ' ' . get_bloginfo('name');
            $email_body = esc_html__("You have received an appointment request from: ", 'pearl-medical-framework') . $from_name . " <br/>";

            if (!empty($phone_number)) {
                $email_body .= esc_html__("Phone Number : ", 'pearl-medical-framework') . $phone_number . " <br/>";
            }

            if (!empty($app_date)) {
                $email_body .= esc_html__("Appointment Date : ", 'pearl-medical-framework') . $app_date . " <br/>";
            }

            $email_body .= esc_html__("Their additional message is as follows.", 'pearl-medical-framework') . " <br/>";
            $email_body .= wpautop($message) . " <br/>";
            $email_body .= esc_html__("You can contact ", 'pearl-medical-framework') . $from_name . esc_html__(" via email, ", 'pearl-medical-framework') . $from_email;

            $header = 'Content-type: text/html; charset=utf-8' . "\r\n";
            $header = apply_filters("pearl_appointment_mail_header", $header);
            $header .= 'From: ' . $from_name . " <" . $from_email . "> \r\n";

            if (wp_mail($to_email, $email_subject, $email_body, $header)) {
                echo json_encode(array(
                    'success' => true,
                    'message' => esc_html__("Request Sent Successfully!", 'pearl-medical-framework')
                ));
            } else {
                echo json_encode(array(
                        'success' => false,
                        'message' => esc_html__("Server Error: WordPress mail function failed!", 'pearl-medical-framework')
                    )
                );
            }

        else:
            echo json_encode(array(
                    'success' => false,
                    'message' => esc_html__("Invalid Request !", 'pearl-medical-framework')
                )
            );
        endif;

        die;
    }

    add_action('wp_ajax_nopriv_pearl_appointment_request', 'pearl_appointment_request');
    add_action('wp_ajax_pearl_appointment_request', 'pearl_appointment_request');

}