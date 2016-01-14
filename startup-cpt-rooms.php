<?php
/*
Plugin Name: StartUp CPT Rooms
Description: Le plugin pour activer le Custom Post Rooms
Author: Yann Caplain
Version: 0.3.0
Text Domain: startup-cpt-rooms
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//GitHub Plugin Updater
function startup_cpt_rooms_updater() {
	include_once 'lib/updater.php';
	//define( 'WP_GITHUB_FORCE_UPDATE', true );
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

//add_action( 'init', 'startup_cpt_rooms_updater' );

//CPT
function startup_cpt_rooms() {
	$labels = array(
		'name'                => _x( 'Rooms', 'Post Type General Name', 'startup-cpt-rooms' ),
		'singular_name'       => _x( 'Room', 'Post Type Singular Name', 'startup-cpt-rooms' ),
		'menu_name'           => __( 'Rooms (b)', 'startup-cpt-rooms' ),
		'name_admin_bar'      => __( 'Rooms', 'startup-cpt-rooms' ),
		'parent_item_colon'   => __( 'Parent Item:', 'startup-cpt-rooms' ),
		'all_items'           => __( 'All Items', 'startup-cpt-rooms' ),
		'add_new_item'        => __( 'Add New Item', 'startup-cpt-rooms' ),
		'add_new'             => __( 'Add New', 'startup-cpt-rooms' ),
		'new_item'            => __( 'New Item', 'startup-cpt-rooms' ),
		'edit_item'           => __( 'Edit Item', 'startup-cpt-rooms' ),
		'update_item'         => __( 'Update Item', 'startup-cpt-rooms' ),
		'view_item'           => __( 'View Item', 'startup-cpt-rooms' ),
		'search_items'        => __( 'Search Item', 'startup-cpt-rooms' ),
		'not_found'           => __( 'Not found', 'startup-cpt-rooms' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'startup-cpt-rooms' )
	);
	$args = array(
		'label'               => __( 'projects', 'startup-cpt-rooms' ),
		'description'         => __( 'Post Type Description', 'startup-cpt-rooms' ),
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

add_action( 'init', 'startup_cpt_rooms', 0 );

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_cpt_rooms_rewrite_flush() {
    startup_cpt_rooms();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_cpt_rooms_rewrite_flush' );

// Capabilities

function startup_cpt_rooms_caps() {
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

register_activation_hook( __FILE__, 'startup_cpt_rooms_caps' );

// Room types taxonomy
function startup_reloaded_room_types() {
	$labels = array(
		'name'                       => _x( 'Room Types', 'Taxonomy General Name', 'startup-cpt-rooms' ),
		'singular_name'              => _x( 'Room Type', 'Taxonomy Singular Name', 'startup-cpt-rooms' ),
		'menu_name'                  => __( 'Room Types', 'startup-cpt-rooms' ),
		'all_items'                  => __( 'All Items', 'startup-cpt-rooms' ),
		'parent_item'                => __( 'Parent Item', 'startup-cpt-rooms' ),
		'parent_item_colon'          => __( 'Parent Item:', 'startup-cpt-rooms' ),
		'new_item_name'              => __( 'New Item Name', 'startup-cpt-rooms' ),
		'add_new_item'               => __( 'Add New Item', 'startup-cpt-rooms' ),
		'edit_item'                  => __( 'Edit Item', 'startup-cpt-rooms' ),
		'update_item'                => __( 'Update Item', 'startup-cpt-rooms' ),
		'view_item'                  => __( 'View Item', 'startup-cpt-rooms' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'startup-cpt-rooms' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'startup-cpt-rooms' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'startup-cpt-rooms' ),
		'popular_items'              => __( 'Popular Items', 'startup-cpt-rooms' ),
		'search_items'               => __( 'Search Items', 'startup-cpt-rooms' ),
		'not_found'                  => __( 'Not Found', 'startup-cpt-rooms' )
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
/**
 * Detection de CMB2. Identique dans tous les plugins.
 */
if ( !function_exists( 'cmb2_detection' ) ) {
    function cmb2_detection() {
        if ( !is_plugin_active('CMB2/init.php')  && !function_exists( 'startup_reloaded_setup' ) ) {
            add_action( 'admin_notices', 'cmb2_notice' );
        }
    }

    function cmb2_notice() {
        if ( current_user_can( 'activate_plugins' ) ) {
            echo '<div class="error message"><p>' . __( 'CMB2 plugin or StartUp Reloaded theme must be active to use custom metaboxes.', 'startup-cpt-rooms' ) . '</p></div>';
        }
    }

    add_action( 'init', 'cmb2_detection' );
}

function startup_cpt_rooms_meta() {
    require get_template_directory() . '/inc/font-awesome.php';
    
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_cpt_rooms_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Room details', 'startup-cpt-rooms' ),
		'object_types'  => array( 'rooms' )
	) );
    
    $services = $cmb_box->add_field( array(
		'id'          => $prefix . 'services',
		'type'        => 'group',
		'options'     => array(
			'group_title'   => __( 'Service {#}', 'startup-cpt-rooms' ), // {#} gets replaced by row number
			'add_button'    => __( 'Add Another Entry', 'startup-cpt-rooms' ),
			'remove_button' => __( 'Remove Entry', 'startup-cpt-rooms' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );
    
    $cmb_box->add_group_field( $services, array(
        'name'             => __( 'Service name', 'startup-cpt-rooms' ),
        'id'               => 'service',
        'type'             => 'text'
    ) );
    
    $cmb_box->add_group_field( $services, array(
        'name'             => __( 'Service icon', 'startup-cpt-rooms' ),
        'id'               => 'service-icon',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => $font_awesome
    ) );
}

add_action( 'cmb2_admin_init', 'startup_cpt_rooms_meta' );
?>
