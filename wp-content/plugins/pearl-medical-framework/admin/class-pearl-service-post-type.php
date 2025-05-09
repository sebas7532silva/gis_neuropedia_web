<?php
/**
 * Service custom post type class.
 *
 * Defines the service post type.
 *
 * @package    Pearl_Medical_Framework
 * @subpackage Pearl_Medical_Framework/admin
 * @author     Fahid Javid <fahidjavid@icloud.com>
 */

class Pearl_Service_Post_Type {

    /**
     * Register service post type
     * @since 1.0.0
     */
    public function register_service_post_type() {

        $labels = array(
            'name'                => _x( 'Services', 'Post Type General Name', 'pearl-medical-framework' ),
            'singular_name'       => _x( 'Service', 'Post Type Singular Name', 'pearl-medical-framework' ),
            'menu_name'           => __( 'Services', 'pearl-medical-framework' ),
            'name_admin_bar'      => __( 'Service', 'pearl-medical-framework' ),
            'parent_item_colon'   => __( 'Parent Service:', 'pearl-medical-framework' ),
            'all_items'           => __( 'All Services', 'pearl-medical-framework' ),
            'add_new_item'        => __( 'Add New Service', 'pearl-medical-framework' ),
            'add_new'             => __( 'Add New', 'pearl-medical-framework' ),
            'new_item'            => __( 'New Service', 'pearl-medical-framework' ),
            'edit_item'           => __( 'Edit Service', 'pearl-medical-framework' ),
            'update_item'         => __( 'Update Service', 'pearl-medical-framework' ),
            'view_item'           => __( 'View Service', 'pearl-medical-framework' ),
            'search_items'        => __( 'Search Service', 'pearl-medical-framework' ),
            'not_found'           => __( 'Not found', 'pearl-medical-framework' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'pearl-medical-framework' ),
        );

        $rewrite = array(
            'slug'                => __( 'service', 'pearl-medical-framework' ),
            'with_front'          => true,
            'pages'               => true,
        );

        $args = array(
            'label'               => __( 'Services', 'pearl-medical-framework' ),
            'description'         => __( 'Services', 'pearl-medical-framework' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'thumbnail', 'editor' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 10,
            'menu_icon'           => 'dashicons-admin-generic',
            'show_in_admin_bar'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'rewrite'             => $rewrite,
            'capability_type'     => 'post',
        );

        register_post_type( 'service', apply_filters('pearl_services_post_type_args', $args ) );

    }
}