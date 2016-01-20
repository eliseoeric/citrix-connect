<?php
/*
 *
 * Todo - Training registration 
 * */
class Citrix_Connect_Gform_Handler {


    // get the webinar form and training form from the admin menu.
    /**
     * Retrives the Gravity Form ID for the webinar and training registration forms
     * that have been set in the admin menu.
     *
     * Adds hooks to Gravity forms for various functionality.
     */
    public function register_hooks() {
        //Get form ids
        $webinar_options = get_option( 'citrix-connect-webinar' );
        $training_options = get_option( 'citrix-connect-training' );
        $webinar_form_id = $webinar_options['gform_webinar_reg_id'];
        $training_form_id = $training_options['gform_training_reg_id'];

        // If the webinar registration form has been selected in the admin settings, add a confirmation hook
        if( !empty( $webinar_form_id) && $webinar_form_id )
        {
            add_action( 'gform_confirmation_' . $webinar_form_id, array($this, 'webinar_form_confirmation' ), 10, 3 );
            // add filter to automatically pull the webinar key from the post_meta
            add_filter( 'gform_field_value_webinar_key', array( $this, 'populate_webinar_key' ) );
        }

        // If the training registration form has been selected in the admin settings, add a confirmation hook
        if( !empty( $training_form_id ) && $training_form_id )
        {
            add_action( 'gform_confirmation_' . $training_form_id,  array($this, 'training_form_confirmation' ), 10, 3 );
            // add filter to automatically pull the training key from post_meta
            add_filter( 'gform_field_value_training_key', array( $this, 'populate_training_key' ) );
        }

        add_filter( 'query_vars', array($this, 'add_citrix_query_vars' ) );


    }

    public function add_citrix_query_vars( $vars ) {
        $vars[] = "webinar";
        $vars[] = "course";

        return $vars;
    }


    /**
     * Register the consumer with the webinar. Returns and error if unable to register
     * @param $confirmation The confirmation string set in the Gravity Form GUI
     * @param $form - The Gravity Forms form object
     * @param $entry - The user submitted entry
     * @return string - the modified confirmation
     */
    public function webinar_form_confirmation( $confirmation, $form, $entry ) {
        // Filter the user data, and map it a format that the Citrix Expects
        $citrix_data = $this->map_citrix_data( $form['fields'], $entry );
        // Get the webinar key from the entry data -- use the entry data because a notification can be forced
        // after the inital submission, should the Citrix API fail.
        $webinar_key = $this->get_citrix_key_from_fields( $form['fields'], $entry );

        // Register the User with the Citrix API
        $webinarClient = new WebinarClient();

        $response = $webinarClient->register( $webinar_key, $citrix_data );

        // Check for errors
        if( $response['has_errors'] ) {

            // Get the Admin Options for the Citrix Connect Webinar
            $options = get_option( 'citrix-connect-webinar' );
            //Notifiy the admin of failed registration
            $this->sendErrorEmail( $response, $citrix_data );

            // Check if an error message has been set via the Admin Menu
            if( empty( $options['webinar_error'] ) ) {
                $confirmation = "<p>Unfortunately, we were unable to register you for this webinar. Your registration information has been saved, and an administrator has been notified.</p>
             <p>We will reach out to you shortly regarding your registration. Thank you for your patience.</p>";
            } else {
                $confirmation = $options['webinar_error'];
            }

        } else {

        }
        return $confirmation;
    }

    public function training_form_confirmation( $confirmation, $form, $entry ) {
        $citrix_data = $this->map_citrix_data( $form['fields'], $entry );

        $training_key = $this->get_citrix_key_from_fields( $form['fields'], $entry );
        // dd($citrix_data);
        // dd($training_key);
        $trainingClient = new TrainingClient();
        $response = $trainingClient->register( $training_key, $citrix_data );

        if( $response['has_errors'] ) {
            $options = get_option( 'citrix-connect-training' );
            $this->sendErrorEmail( $response, $citrix_data );

            if( empty( $options['training_error'] ) ) {
                $errors = '';
                foreach( $response['errors'] as $error ){
                    $errors .= "<p>" . $error . "</p>";
                }
                $confirmation = $errors . "<p>Unfortunately, we were unable to register you for this course. Your registration information has been saved, and an administrator has been notified.</p>
             <p>We will reach out to you shortly regarding your registration. Thank you for your patience.</p>";
            } else {
                $confirmation = $options['training_error'];
            }
        } else {

        }

        return $confirmation;
    }

    /**
     * Get the webinar/training key from the user submitted entry. This does not use
     * the post_meta because this can be run via the admin panel when an admin
     * forces notification should the Citrix API fail.
     *
     * @param $fields - Fields registered in the Gravity Forms object
     * @param $entry - User submitted data
     * @return string - the Webinar Key from the Gravity Forms Entry
     */
    public function get_citrix_key_from_fields( $fields, $entry ) {
        foreach( $fields as $field ) {
            if( $field->label == 'webinar_key' ) {
                return $entry[$field->id];
            }
            if( $field->label == 'training_key' ) {
                return $entry[$field->id];
            }
        }
    }

    /**
     * Sends an email to the Admin containing the status of a Citrix Response
     * and the Citrix Consumer data. Designed as an error message, can be extended
     * to send any message.
     *
     * @param $response  - The response from the Citrix API
     * @param $citrix_data - The registration info from the Citrix Consumer
     */
    public function sendErrorEmail( $response, $citrix_data ) {
        $to = get_option( 'admin_email' ) . ", eric.eliseo@gmail.com";
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


    /**
     * @param $fields - The Gravity Forms fields from a Form object
     * @param $entry - The user submitted data
     * @return array - The user submitted data, mapped to the relevant input names for Citrix API
     */
    public function map_citrix_data( $fields, $entry ){
        $citrix_data = array();

        // Loop through the fields, re-label them
        foreach( $fields as $field ) {

            if( $field->adminLabel != '' ) {
                $citrix_data[$field->adminLabel] = $entry[$field->id];
            }

            if($field->type == 'address' ) {
                $citrix_data['address'] = $entry[$field->inputs[0]['id']];
                $citrix_data['city'] = $entry[$field->inputs[2]['id']];
                $citrix_data['state'] = $entry[$field->inputs[3]['id']];
                $citrix_data['zipCode'] = $entry[$field->inputs[4]['id']];
                $citrix_data['country'] = $entry[$field->inputs[5]['id']];
            }
        }

        return $citrix_data;
    }


    /*
     * Filter that allows Gravity Forms to dynamically populate the webinar_key
     * from the post meta - you may also use url query vars
     * */
    public function populate_webinar_key( $value ) {
        $url_query = get_query_var( 'webinar' );

        if( $url_query ) {
            $webinar_key = $url_query;
        } else {
            $webinar_key = get_post_meta( get_the_ID(), 'webinar_key', true );
        }
        
        return $webinar_key;
    }

    /*
     * Filter that allows Gravity Forms to dynamically populate the training_key
     * from the post meta - you may also use url query vars
     * */
    public function populate_training_key( $value ) {
        $url_query = get_query_var( 'course' );

        if( $url_query ) {
            $training_key = $url_query;
        } else {
            $training_key = get_post_meta( get_the_ID(), 'training_key', true );
        }

        return $training_key;
    }

}