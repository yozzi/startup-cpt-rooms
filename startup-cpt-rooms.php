<?php
/*
Plugin Name: StartUp Rooms
Description: Le plugin pour activer le Custom Post Rooms
Author: Yann Caplain
Version: 0.4.0
*/

//GitHub Plugin Updater
function startup_reloaded_rooms_updater() {
	include_once 'lib/updater.php';
	define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) {
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'startup-cpt-rooms',
			'api_url' => 'https://api.github.com/repos/yozzi/startup-cpt-rooms',
			'raw_url' => 'https://raw.github.com/yozzi/startup-cpt-rooms/master',
			'github_url' => 'https://github.com/yozzi/startup-cpt-rooms',
			'zip_url' => 'https://github.com/yozzi/startup-cpt-rooms/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}

add_action( 'init', 'startup_reloaded_rooms_updater' );

//CPT
function startup_reloaded_rooms() {
	$labels = array(
		'name'                => _x( 'Rooms', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Room', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Rooms', 'text_domain' ),
		'name_admin_bar'      => __( 'Rooms', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Items', 'text_domain' ),
		'add_new_item'        => __( 'Add New Item', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'new_item'            => __( 'New Item', 'text_domain' ),
		'edit_item'           => __( 'Edit Item', 'text_domain' ),
		'update_item'         => __( 'Update Item', 'text_domain' ),
		'view_item'           => __( 'View Item', 'text_domain' ),
		'search_items'        => __( 'Search Item', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' )
	);
	$args = array(
		'label'               => __( 'projects', 'text_domain' ),
		'description'         => __( 'Post Type Description', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'revisions' ),
		//'taxonomies'          => array( 'project_types' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-admin-network',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
        'capability_type'     => array('room','rooms'),
        'map_meta_cap'        => true
	);
	register_post_type( 'rooms', $args );

}

add_action( 'init', 'startup_reloaded_rooms', 0 );

// Capabilities

function startup_reloaded_rooms_caps() {
	$role_admin = get_role( 'administrator' );
	$role_admin->add_cap( 'edit_room' );
	$role_admin->add_cap( 'read_room' );
	$role_admin->add_cap( 'delete_room' );
	$role_admin->add_cap( 'edit_others_rooms' );
	$role_admin->add_cap( 'publish_rooms' );
	$role_admin->add_cap( 'edit_rooms' );
	$role_admin->add_cap( 'read_private_rooms' );
	$role_admin->add_cap( 'delete_rooms' );
	$role_admin->add_cap( 'delete_private_rooms' );
	$role_admin->add_cap( 'delete_published_rooms' );
	$role_admin->add_cap( 'delete_others_rooms' );
	$role_admin->add_cap( 'edit_private_rooms' );
	$role_admin->add_cap( 'edit_published_rooms' );
}

register_activation_hook( __FILE__, 'startup_reloaded_rooms_caps' );

// Room types taxonomy
function startup_reloaded_room_types() {
	$labels = array(
		'name'                       => _x( 'Room Types', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Room Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Room Types', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' )
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false
	);
	register_taxonomy( 'room-type', array( 'rooms' ), $args );

}

add_action( 'init', 'startup_reloaded_room_types', 0 );

// Retirer la boite de la taxonomie sur le coté
function startup_reloaded_room_types_metabox_remove() {
	remove_meta_box( 'tagsdiv-room-type', 'rooms', 'side' );
    // tagsdiv-project_types pour les taxonomies type tags
    // custom_taxonomy_slugdiv pour les taxonomies type categories
}

add_action( 'admin_menu' , 'startup_reloaded_room_types_metabox_remove' );

// Metaboxes
function startup_reloaded_rooms_meta() {
    require get_template_directory() . '/inc/font-awesome.php';
    
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_reloaded_rooms_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Room details', 'cmb2' ),
		'object_types'  => array( 'rooms' )
	) );
    
    $services = $cmb_box->add_field( array(
		'id'          => $prefix . 'services',
		'type'        => 'group',
		'options'     => array(
			'group_title'   => __( 'Service {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'    => __( 'Add Another Entry', 'cmb2' ),
			'remove_button' => __( 'Remove Entry', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );
    
    $cmb_box->add_group_field( $services, array(
        'name'             => __( 'Service name', 'cmb2' ),
        'id'               => 'service',
        'type'             => 'text'
    ) );
    
    $cmb_box->add_group_field( $services, array(
        'name'             => __( 'Service icon', 'cmb2' ),
        'id'               => 'service-icon',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => $font_awesome
    ) );
}

add_action( 'cmb2_init', 'startup_reloaded_rooms_meta' );
?>
