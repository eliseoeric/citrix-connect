<?php
/*
 * [webinar_url button="true" id="125" text="override title"]
 *
 * Webinar Registration URL/Button Shortcode
 * Returns the Webinar's registration url. Can be optionally presented as a button.
 * All attributes are optional.
 */
function citrix_connect_webinar_url ($atts, $content ) {
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
        $webinarAPI = new WebinarClient();
        $title = $webinarAPI->getTitle( $id );
    }

    //Wrap the title in the given tag
    $html = "<" . $a['tag'] . ">" . esc_html( $title ) . "</" . $a['tag'] . ">";

    return $html;
}

add_shortcode( 'webinar_url', 'citrix_connect_webinar_url' );