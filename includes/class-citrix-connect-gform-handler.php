<?php

class Citrix_Connect_Gform_Handler {


    // get the webinar form and training form from the admin menu.
    public function register_hooks() {
        $webinar_options = get_option( 'citrix-connect-webinar' );
        $training_options = get_option( 'citrix-connect-training' );
        $webinar_form_id = $webinar_options['gform_webinar_reg_id'];
        $training_form_id = $training_options['gform_training_reg_id'];
        // If the webinar registration form has been selected in the admin settings, add a confirmation hook
        if( !empty( $webinar_form_id) && $webinar_form_id )
        {
            add_action( 'gform_confirmation_' . $webinar_form_id, array($this, 'webinar_form_confirmation' ), 10, 3 );
            add_filter( 'gform_field_value_webinar_key', array( $this, 'populate_webinar_key' ) );
        }

        // If the training registration form has been selected in the admin settings, add a confirmation hook
        if( !empty( $training_form_id ) && $training_form_id )
        {
            add_action( 'gform_confirmation_' . $training_form_id,  array($this, 'training_form_confirmation' ), 10, 3 );
            add_filter( 'gform_field_value_training_key', array( $this, 'populate_training_key' ) );
        }


    }
    /*
     * Register the consumer with the webinar. Returns and error if unable to register
     *
     */
    public function webinar_form_confirmation( $confirmation, $form, $entry ) {
//        dd($entry);
        $citrix_data = $this->map_citrix_data( $form['fields'], $entry );
        $webinar_key = $this->get_webinar_field_from_fields( $form['fields'], $entry );

        $webinarClient = new WebinarClient();
        $response = $webinarClient->register( $webinar_key, $citrix_data );
        if( $response['has_errors'] ) {
            $options = get_option( 'citrix-connect-webinar' );

            //Notifiy the admin of failed registration
            $this->sendErrorEmail( $response, $citrix_data );

            if( empty( $options['webinar_error'] ) ) {
                $confirmation = "<p>Unfortunately, we were unable to register you for this webinar. Your registration information has been saved, and an administrator has been notified.</p>
             <p>We will reach out to you shortly regarding your registration. Thank you for your patience.</p>";
            } else {
                $confirmation = $options['webinar_error'];
            }

        } else {
            $confirmation .= "<p>Your JoinUrl is: <a href='" . $response['joinUrl'] ."'>" .$response['joinUrl'] . "</a>";
        }
        return $confirmation;

    }

    public function get_webinar_field_from_fields( $fields, $entry ) {
        foreach( $fields as $field ) {
            if( $field->adminLabel == 'webinar_key' ) {
                return $entry[$field->id];
            }
        }
    }

    public function sendErrorEmail( $response, $citrix_data ) {
        $to = get_option( 'admin_email' );
        $subject = 'Failed Citrix Registration';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $body = "<p>There has been a failed attempt to register for a webinar. The user\'s information has been saved in the Gravity Forms database.</p>
        <ul><li>Name: " . $citrix_data['firstName'] . " " .$citrix_data['lastName'] . "</li><li>Email Address: " . $citrix_data['email'] ."</li></ul>";
        $errors = "<ul>";
        foreach( $response['errors'] as $error ){
            $errors .= "<li>" . $error . "</li>";
        }
        $errors .= "</ul>";
        $body .= $errors;

        wp_mail( $to, $subject, $body, $headers );
    }
    public function map_citrix_data( $fields, $entry ){
        $citrix_data = array();
        foreach( $fields as $field ) {
            if( $field->adminLabel == 'firstName' ) {
                $citrix_data['firstName'] = $entry[$field->id];
            }

            if( $field->adminLabel == 'lastName' ) {
                $citrix_data['lastName'] = $entry[$field->id];
            }

            if( $field->adminLabel == 'email' ) {
                $citrix_data['email'] = $entry[$field->id];
            }

            if( $field->adminLabel == 'phone' ) {
                $citrix_data['phone'] = $entry[$field->id];
            }

            if( $field->adminLabel == 'companyUrl' ) {
                $citrix_data['companyUrl'] = $entry[$field->id];
            }
        }

        return $citrix_data;
    }
    /*
     * Filter that allows Gravity Forms to dynamically populate the webinar_key
     * from the post meta - you may also use url query vars
     * */
    public function populate_webinar_key( $value ) {
        $webinar_key = get_post_meta( get_the_ID(), 'webinar_key', true );
        return $webinar_key;
    }

    /*
     * Filter that allows Gravity Forms to dynamically populate the training_key
     * from the post meta - you may also use url query vars
     * */
    public function populate_training_key( $value ) {
        $training_key = get_post_meta( get_the_ID(), 'training_key', true );
        return $training_key;
    }

}