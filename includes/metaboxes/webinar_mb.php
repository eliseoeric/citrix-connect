<?php
//need to include
// 1. price
// 2 sale price
// speakers
// registration url or link
// learn more link
// tabs section -- this could just be from elegant tabs
// featured image
// similar webinars
function webinar_mb( $meta_boxes ) {
    $prefix = "";

    $webinar = new_cmb2_box(
        array(
            'id'         => 'webinar-data',
            'title'      => 'Webinar Details',
            'object_types'      => array( 'citrix_webinar' ),
            'show_on'    =>
                array(
//                    'key' => 'page-template',
//                    'value' => array('template-location.php','template-events.php','template-reviews.php')
                ),
            'context'    => 'side',
            'priority'   => 'default',
            'show_names' => true,
        )
    );
    $webinar->add_field( array(
        'name' => 'Webinar Key',
//        'desc' => 'Extra content that is below the hero',
        'id' => $prefix . 'webinar_key',
        'type' => 'text',
        'default' => get_old_webinar_key(),
        'options' => array()
    ) );

    $webinar->add_field( array(
        'name' => 'PDF Url',
//        'desc' => 'Extra content that is below the hero',
        'id' => $prefix . 'webinar_pdf',
        'type' => 'file',
        'default' => get_old_pdf_value(),
        'options' => array()
    ) );

}

function get_old_pdf_value(  ) {
    $id = $_GET['post'];
    $pdf = get_post_meta( $id, '_BPCW_pdf_download', true );
    if( $pdf ) {
        return $pdf;
    } else {
        return;
    }
}


function get_old_webinar_key() {
    $id = $_GET['post'];
    $webinar_id = get_post_meta( $id, '_BPCW_webinar_id', true );
    if( $webinar_id ) {
        return $webinar_id;
    } else {
        return;
    }
}


function webinar_add_registrants_metabox() {
    add_meta_box(
        'webinar_registrants',
        'Webinar Registrants',
        'webinar_render_add_registrants_metabox',
        'citrix_webinar'
    );
}

function webinar_render_add_registrants_metabox( $post ) {
    wp_enqueue_script( 'datatables' );
    wp_enqueue_style( 'datatables' );
    wp_enqueue_script( 'cc_datatables' );
    $webinarClient = new WebinarClient();
    $registrants = $webinarClient->getRegistrants( get_post_meta( $post->ID, 'webinar_key', true) );
//    dd($registrants);

    if( empty( $registrants ) ) {
        $message = "<p class='error'>There are currently no registrants in the system.</p>";
    } else {
        $message = "<p>Below is a list of current registrants for this webinar.</p>";
    }

//    echo "<h2>Registrants</h2>";
    echo $message;

    if( count( $registrants ) > 0 )
    {
        echo "<div class='upcomming-data-table'>";
        echo '<table data-order=\'[[ 3, "desc" ]]\'>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>First Name</th>';
        echo '<th>Last Name</th>';
        echo '<th>Email</th>';
        echo '<th>Status</th>';
        echo '<th>Date</th>';
        echo '<th>Zone</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ( $registrants as $registrant )
        {
//				dd($webinar);
//            $start = date('Y-m-d', strtotime($webinar->times[0]['startTime']));
//            $end = date('Y-m-d', strtotime($webinar->times[0]['endTime']));
            $time_zone = explode( '/', $registrant->timeZone );
            echo '<tr>';
            echo '<td>' . $registrant->firstName . '</td>';
            echo '<td>' . $registrant->lastName . '</td>';
            echo '<td>' . $registrant->email . '</td>';
            echo '<td>' . strtolower( $registrant->status ) . '</td>';
            echo '<td class="date">' . date( 'Y-m-d', strtotime( $registrant->registrationDate ) ) . '</td>';
            echo '<td>' . str_replace( '_', ' ', $time_zone[ 1 ] ) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }
}