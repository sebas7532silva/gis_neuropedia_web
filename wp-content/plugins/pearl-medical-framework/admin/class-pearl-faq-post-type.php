<?php
/**
 * FAQ custom post type class.
 *
 * Defines the faq post type.
 *
 * @package    Pearl_Medical_Framework
 * @subpackage Pearl_Medical_Framework/admin
 * @author     Fahid Javid <fahidjavid@icloud.com>
 */

class Pearl_FAQ_Post_Type {

    /**
     * Register faq post type
     * @since 1.0.0
     */
    public function register_faq_post_type() {

        $labels = array(
            'name'                => _x( 'FAQs', 'Post Type General Name', 'pearl-medical-framework' ),
            'singular_name'       => _x( 'FAQ', 'Post Type Singular Name', 'pearl-medical-framework' ),
            'menu_name'           => __( 'FAQs', 'pearl-medical-framework' ),
            'name_admin_bar'      => __( 'FAQ', 'pearl-medical-framework' ),
            'parent_item_colon'   => __( 'Parent FAQ:', 'pearl-medical-framework' ),
            'all_items'           => __( 'All FAQs', 'pearl-medical-framework' ),
            'add_new_item'        => __( 'Add New FAQ', 'pearl-medical-framework' ),
            'add_new'             => __( 'Add New', 'pearl-medical-framework' ),
            'new_item'            => __( 'New FAQ', 'pearl-medical-framework' ),
            'edit_item'           => __( 'Edit FAQs', 'pearl-medical-framework' ),
            'update_item'         => __( 'Update FAQ', 'pearl-medical-framework' ),
            'view_item'           => __( 'View FAQ', 'pearl-medical-framework' ),
            'search_items'        => __( 'Search FAQ', 'pearl-medical-framework' ),
            'not_found'           => __( 'Not found', 'pearl-medical-framework' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'pearl-medical-framework' ),
        );

        $args = array(
            'label'               => __( 'FAQs', 'pearl-medical-framework' ),
            'description'         => __( 'FAQs', 'pearl-medical-framework' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', ),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 10,
            'menu_icon'           => 'dashicons-groups',
            'show_in_admin_bar'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'rewrite'             => false,
            'capability_type'     => 'post',
        );

        register_post_type( 'faq', apply_filters('pearl_faq_post_type_args', $args ) );

    }


    /**
     * Register meta boxes related to faq post type
     *
     * @param   array   $meta_boxes
     * @since   1.0.0
     * @return  array   $meta_boxes
     */
    public function register_meta_boxes ( $meta_boxes ) {

        $prefix = 'PEARL_META_';

        // FAQs Meta Box
        $meta_boxes[] = array(
            'id' => 'faq-meta-box',
            'title' => __('FAQ Data', 'pearl-medical-framework'),
            'pages' => array('faq'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name' => __('Icon', 'pearl-medical-framework'),
                    'id' => "{$prefix}icon",
                    'type' => 'text'
                )
            )
        );

        // apply a filter before returning meta boxes
        $meta_boxes = apply_filters( 'faq_meta_boxes', $meta_boxes );

        return $meta_boxes;
    }

}