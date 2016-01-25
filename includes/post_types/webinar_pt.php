<?php
function register_webinars() {
    // creating (registering) the custom type
    register_post_type( 'citrix_webinar', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
        // let's now add all the options for this post type
        array ( 'labels'              => array (
            'name'               => __( 'Webinars', 'citirx-connect' ), /* This is the Title of the Group */
            'singular_name'      => __( 'Webinar', 'citirx-connect' ), /* This is the individual type */
            'all_items'          => __( 'All Webinars', 'citirx-connect' ), /* the all items menu item */
            'add_new'            => __( 'Add New', 'citirx-connect' ), /* The add new menu item */
            'add_new_item'       => __( 'Add New Webinar', 'citirx-connect' ), /* Add New Display Title */
            'edit'               => __( 'Edit', 'citirx-connect' ), /* Edit Dialog */
            'edit_item'          => __( 'Edit Webinar', 'citirx-connect' ), /* Edit Display Title */
            'new_item'           => __( 'New Webinar', 'citirx-connect' ), /* New Display Title */
            'view_item'          => __( 'View Webinar', 'citirx-connect' ), /* View Display Title */
            'search_items'       => __( 'Search Webinars', 'citirx-connect' ), /* Search Custom Type Title */
            'not_found'          => __( 'Nothing found in the Database.', 'citirx-connect' ), /* This displays if there are no entries yet */
            'not_found_in_trash' => __( 'Nothing found in Trash', 'citirx-connect' ), /* This displays if there is nothing in the trash */
            'parent_item_colon'  => ''
        ), /* end of arrays */
                'description'         => __( 'Post type that corresponds to the Citrix GoToWebinar Entity', 'citirx-connect' ), /* Custom Type Description */
                'public'              => true,
                'publicly_queryable'  => true,
                'exclude_from_search' => false,
                'show_ui'             => true,
                'query_var'           => true,
                'menu_position'       => 8, /* this is what order you want it to appear in on the left hand side menu */
                'menu_icon'           => 'dashicons-images-alt2', /* the icon for the custom post type menu */
               'rewrite'             => array ( 'slug' => 'webinars', 'with_front' => false ), /* you can specify its url slug */
                'has_archive'         => 'webinars', /* you can rename the slug here */
                'capability_type'     => 'post',
                'hierarchical'        => false,
            /* the next one is important, it tells what's enabled in the post editor */
                'supports'            => array ( 'title', 'editor', 'thumbnail', 'excerpt' )
        ) /* end of options */
    ); /* end of register post type */

    /* this adds your post categories to your custom post type */
     register_taxonomy_for_object_type( 'category', 'citrix_webinar' );

}

// adding the function to the Wordpress init
add_action( 'init', 'register_webinars' );

//
//register_taxonomy( 'webinars',
//    array ( 'webinar' ), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
//    array ( 'hierarchical'      => true,     /* if this is true, it acts like categories */
//            'labels'            => array (
//                'name'              => __( 'Webinar Categories', 'UMA' ), /* name of the custom taxonomy */
//                'singular_name'     => __( 'Webinar Category', 'UMA' ), /* single taxonomy name */
//                'search_items'      => __( 'Search Webinar Categories', 'UMA' ), /* search title for taxomony */
//                'all_items'         => __( 'All Webinar Categories', 'UMA' ), /* all title for taxonomies */
//                'parent_item'       => __( 'Parent Webinar Category', 'UMA' ), /* parent title for taxonomy */
//                'parent_item_colon' => __( 'Parent Webinar Category:', 'UMA' ), /* parent taxonomy title */
//                'edit_item'         => __( 'Edit Webinar Category', 'UMA' ), /* edit custom taxonomy title */
//                'update_item'       => __( 'Update Webinar Category', 'UMA' ), /* update title for taxonomy */
//                'add_new_item'      => __( 'Add New Webinar Category', 'UMA' ), /* add new title for taxonomy */
//                'new_item_name'     => __( 'New Webinar Name', 'UMA' ) /* name title for taxonomy */
//            ),
//            'show_admin_column' => true,
//            'show_ui'           => true,
//            'query_var'         => true,
//            'rewrite'           => array ( 'slug' => 'webinars' ),
//    )
//);
