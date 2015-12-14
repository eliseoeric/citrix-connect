<?php 
class BP_Post_Handler{

	private static $instance;

	private function __construct(){
		$this->register_posts();
		
	}

	public static function init(){
		if(null == self::$instance){
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function register_posts(){
		add_action('init', array($this, 'register_webinar_post'));
		add_action('init', array($this, 'register_training_post'));
	}

	/*
	* Registers the Webinar Custom Post Type
	*/
	public function register_webinar_post(){
		$labels = array(
			'name' => 						__( 'Webinars', 'BPCW' ), /* This is the Title of the Group */
			'singular_name' => 				__( 'Webinar', 'BPCW' ), /* This is the individual type */
			'all_items' =>		 			__( 'All Webinars', 'BPCW' ), /* the all items menu item */
			'add_new' => 					__( 'Add New', 'BPCW' ), /* The add new menu item */
			'add_new_item' => 				__( 'Add New Webinar', 'BPCW' ), /* Add New Display Title */
			'edit' => 						__( 'Edit', 'BPCW' ), /* Edit Dialog */
			'edit_item' => 					__( 'Edit Webinar', 'BPCW' ), /* Edit Display Title */
			'new_item' => 					__( 'New Webinar', 'BPCW' ), /* New Display Title */
			'view_item' => 					__( 'View Webinar Page', 'BPCW' ), /* View Display Title */
			'search_items' => 				__( 'Search Webinars', 'BPCW' ), /* Search Custom Type Title */ 
			'not_found' =>  				__( 'You currently have no webinars scheduled.', 'BPCW' ), /* This displays if there are no entries yet */ 
			'not_found_in_trash' =>	 		__( 'Nothing found in Trash', 'BPCW' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => 			''
			);
	
		$args = array(
			'labels' =>						$labels, 
			'description' => 				__( 'Citirx Webinar registration pages.', 'BPCW' ), /* Custom Type Description */
			'public' => 					true,
			'publicly_queryable' => 		true,
			'exclude_from_search' => 		false,
			'show_ui' => 					true,
			'query_var' => 					true,
			'menu_position' => 				20, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 					'dashicons-welcome-learn-more', /* the icon for the custom post type menu */
			'rewrite'	=> 					array( 'slug' => 'webinars', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 				'webinars', /* you can rename the slug here */
			'capability_type' => 			'post',
			'hierarchical' => 				false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => 					array( 'title', 'thumbnail', 'revisions')
		);

		register_post_type('citrix_webinar', $args); 

		

		$this->register_webinar_taxonomies();
	}

	public function register_webinar_taxonomies(){

		/* this adds your post categories to your custom post type */
		register_taxonomy_for_object_type( 'category', 'citrix_webinar' );
		/* this adds your post tags to your custom post type */
		register_taxonomy_for_object_type( 'post_tag', 'citrix_webinar' );

		$labels = array(
			'name' => 						__( 'Webinar Categories', 'BPCW' ), /* name of the custom taxonomy */
			'singular_name' => 				__( 'Webinar Category', 'BPCW' ), /* single taxonomy name */
			'search_items' =>  				__( 'Search Webinar Categories', 'BPCW' ), /* search title for taxomony */
			'all_items' => 					__( 'All Webinar Categories', 'BPCW' ), /* all title for taxonomies */
			'parent_item' => 				__( 'Parent Webinar Category', 'BPCW' ), /* parent title for taxonomy */
			'parent_item_colon' => 			__( 'Parent Webinar Category:', 'BPCW' ), /* parent taxonomy title */
			'edit_item' => 					__( 'Edit Webinar Category', 'BPCW' ), /* edit custom taxonomy title */
			'update_item' => 				__( 'Update Webinar Category', 'BPCW' ), /* update title for taxonomy */
			'add_new_item' => 				__( 'Add New Webinar Category', 'BPCW' ), /* add new title for taxonomy */
			'new_item_name' => 				__( 'New Webinar Category Name', 'BPCW' ) /* name title for taxonomy */
			);

			// now let's add custom categories (these act like categories)
	register_taxonomy( 'webinar_cat', 
		array('citrix_webinar'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
		array('hierarchical' => true,     /* if this is true, it acts like categories */
			'labels' => $labels,
			'show_admin_column' => true, 
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'webinar-categories' ),
		)
	);

	$labels = array(
		'name' => 							__( 'Webinar Tags', 'BPCW' ), /* name of the custom taxonomy */
		'singular_name' => 					__( 'Webinar Tag', 'BPCW' ), /* single taxonomy name */
		'search_items' =>  					__( 'Search Webinar Tags', 'BPCW' ), /* search title for taxomony */
		'all_items' => 						__( 'All Webinar Tags', 'BPCW' ), /* all title for taxonomies */
		'parent_item' => 					__( 'Parent Webinar Tag', 'BPCW' ), /* parent title for taxonomy */
		'parent_item_colon' =>				__( 'Parent Webinar Tag:', 'BPCW' ), /* parent taxonomy title */
		'edit_item' => 						__( 'Edit Webinar Tag', 'BPCW' ), /* edit custom taxonomy title */
		'update_item' => 					__( 'Update Webinar Tag', 'BPCW' ), /* update title for taxonomy */
		'add_new_item' => 					__( 'Add New Webinar Tag', 'BPCW' ), /* add new title for taxonomy */
		'new_item_name' => 					__( 'New Webinar Tag Name', 'BPCW' )
		);

	// now let's add custom tags (these act like categories)
	register_taxonomy( 'webinar_tag', 
		array('citrix_webinar'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
		array('hierarchical' => false,    /* if this is false, it acts like tags */
			'labels' => $labels,
			'show_admin_column' => true,
			'show_ui' => true,
			'query_var' => true,
		)
	);
	}


	/*
	* Registers the Training Custom Post Type
	*/
	public function register_training_post(){
		$labels = array(
			'name' => 						__( 'Trainings', 'BPCW' ), /* This is the Title of the Group */
			'singular_name' => 				__( 'Training', 'BPCW' ), /* This is the individual type */
			'all_items' =>		 			__( 'All Trainings', 'BPCW' ), /* the all items menu item */
			'add_new' => 					__( 'Add New', 'BPCW' ), /* The add new menu item */
			'add_new_item' => 				__( 'Add New Training', 'BPCW' ), /* Add New Display Title */
			'edit' => 						__( 'Edit', 'BPCW' ), /* Edit Dialog */
			'edit_item' => 					__( 'Edit Training', 'BPCW' ), /* Edit Display Title */
			'new_item' => 					__( 'New Training', 'BPCW' ), /* New Display Title */
			'view_item' => 					__( 'View Training Page', 'BPCW' ), /* View Display Title */
			'search_items' => 				__( 'Search Trainings', 'BPCW' ), /* Search Custom Type Title */ 
			'not_found' =>  				__( 'You currently have no Trainings scheduled.', 'BPCW' ), /* This displays if there are no entries yet */ 
			'not_found_in_trash' =>	 		__( 'Nothing found in Trash', 'BPCW' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => 			''
			);
	
		$args = array(
			'labels' =>						$labels, 
			'description' => 				__( 'Training registration pages.', 'BPCW' ), /* Custom Type Description */
			'public' => 					true,
			'publicly_queryable' => 		true,
			'exclude_from_search' => 		false,
			'show_ui' => 					true,
			'query_var' => 					true,
			'menu_position' => 				20, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 					'dashicons-awards', /* the icon for the custom post type menu */
			'rewrite'	=> 					array( 'slug' => 'training-courses', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 				'training', /* you can rename the slug here */
			'capability_type' => 			'post',
			'hierarchical' => 				false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => 					array( 'title', 'thumbnail', 'revisions')
		);

		register_post_type('citrix_training', $args); 

		

		$this->register_training_taxonomies();
	}

	public function register_training_taxonomies(){

		/* this adds your post categories to your custom post type */
		register_taxonomy_for_object_type( 'category', 'citrix_training' );
		/* this adds your post tags to your custom post type */
		register_taxonomy_for_object_type( 'post_tag', 'citrix_training' );

		$labels = array(
			'name' => 						__( 'Course Categories', 'BPCW' ), /* name of the custom taxonomy */
			'singular_name' => 				__( 'Course Category', 'BPCW' ), /* single taxonomy name */
			'search_items' =>  				__( 'Search Course Categories', 'BPCW' ), /* search title for taxomony */
			'all_items' => 					__( 'All Course Categories', 'BPCW' ), /* all title for taxonomies */
			'parent_item' => 				__( 'Parent Course Category', 'BPCW' ), /* parent title for taxonomy */
			'parent_item_colon' => 			__( 'Parent Course Category:', 'BPCW' ), /* parent taxonomy title */
			'edit_item' => 					__( 'Edit Course Category', 'BPCW' ), /* edit custom taxonomy title */
			'update_item' => 				__( 'Update Course Category', 'BPCW' ), /* update title for taxonomy */
			'add_new_item' => 				__( 'Add New Course Category', 'BPCW' ), /* add new title for taxonomy */
			'new_item_name' => 				__( 'New Course Category Name', 'BPCW' ) /* name title for taxonomy */
			);

			// now let's add custom categories (these act like categories)
	register_taxonomy( 'training_cat', 
		array('citrix_training'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
		array('hierarchical' => true,     /* if this is true, it acts like categories */
			'labels' => $labels,
			'show_admin_column' => true, 
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'training-categories' ),
		)
	);

	$labels = array(
		'name' => 							__( 'Course Tags', 'BPCW' ), /* name of the custom taxonomy */
		'singular_name' => 					__( 'Course Tag', 'BPCW' ), /* single taxonomy name */
		'search_items' =>  					__( 'Search Course Tags', 'BPCW' ), /* search title for taxomony */
		'all_items' => 						__( 'All Course Tags', 'BPCW' ), /* all title for taxonomies */
		'parent_item' => 					__( 'Parent Course Tag', 'BPCW' ), /* parent title for taxonomy */
		'parent_item_colon' =>				__( 'Parent Course Tag:', 'BPCW' ), /* parent taxonomy title */
		'edit_item' => 						__( 'Edit Course Tag', 'BPCW' ), /* edit custom taxonomy title */
		'update_item' => 					__( 'Update Course Tag', 'BPCW' ), /* update title for taxonomy */
		'add_new_item' => 					__( 'Add New Course Tag', 'BPCW' ), /* add new title for taxonomy */
		'new_item_name' => 					__( 'New Course Tag Name', 'BPCW' )
		);

	// now let's add custom tags (these act like categories)
	register_taxonomy( 'training_tag', 
		array('citrix_training'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
		array('hierarchical' => false,    /* if this is false, it acts like tags */
			'labels' => $labels,
			'show_admin_column' => true,
			'show_ui' => true,
			'query_var' => true,
		)
	);
	}
}

 ?>