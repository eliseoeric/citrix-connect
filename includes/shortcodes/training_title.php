<?php
/*
 * [training_title tag="h3" id="125" default="override title"]
 *
 * Training Title shortcode.
 * Return's the Training's Title from the Citrix API
 * All attributes are optional.
 */
function citrix_connect_training_title ($atts, $content ) {
    $a = shortcode_atts(
        array(
            'default' => '', // Override the title that comes from the API (optional)
            'id' => '', // Indicate which Webinar Id should be give (optional, will pull from post)
            'tag' => 'h3' // Wrap the title in a HTML tag
        ),
        $atts
    );

    //if the webinar id has not been set, pull it from the post
    $id = ( empty( $a['id' ] ) ? get_post_meta( get_the_ID(), 'webinar_key', true ) : $a['id'] );
    //Set the title to the default
    $title = $a['default'];

    //If the default title is blank, grab from Citrix API
    if( '' === $title ) {
        //Init the webinar client
        $trainingAPI = new TrainingClient();
        $title = $trainingAPI->getTitle( $id );
    }

    //Wrap the title in the given tag
    $html = "<" . $a['tag'] . ">" . esc_html( $title ) . "</" . $a['tag'] . ">";

    return $html;
}

add_shortcode( 'training_title', 'citrix_connect_training_title' );