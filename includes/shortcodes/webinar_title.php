<?php

function citrix_connect_webinar_title ($atts, $content ) {
	$a = shortcode_atts(
		array(
			'default' => '',
			'id' => '',
			'tag' => 'h3'
		),
		$atts
	);

	$webinarAPI = new WebinarClient();

	$title = $a['default'];

	if( '' === $title ) {
		$title = $webinarAPI->getTitle( $a[ 'id' ] );
	}

	$html = "<" . $a['tag'] . ">" . esc_html( $title ) . "</" . $a['tag'] . ">";

	return $html;
}

add_shortcode( 'webinar_title', 'citrix_connect_webinar_title' );