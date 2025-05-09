<?php
/**
 * Testimonial custom post type class.
 *
 * Defines the testimonial post type.
 *
 * @package    Pearl_Medical_Framework
 * @subpackage Pearl_Medical_Framework/admin
 * @author     Fahid Javid <fahidjavid@icloud.com>
 */

class Pearl_Testimonial_Post_Type {

    /**
     * Register testimonial post type
     * @since 1.0.0
     */
    public function Pearl_Testimonial_Post_Type() {

        $labels = array(
            'name'                => _x( 'Testimonials', 'Post Type General Name', 'pearl-medical-framework' ),
            'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'pearl-medical-framework' ),
            'menu_name'           => __( 'Testimonials', 'pearl-medical-framework' ),
            'name_admin_bar'      => __( 'Testimonial', 'pearl-medical-framework' ),
            'parent_item_colon'   => __( 'Parent Testimonial:', 'pearl-medical-framework' ),
            'all_items'           => __( 'All Testimonials', 'pearl-medical-framework' ),
            'add_new_item'        => __( 'Add New Testimonial', 'pearl-medical-framework' ),
            'add_new'             => __( 'Add New', 'pearl-medical-framework' ),
            'new_item'            => __( 'New Testimonial', 'pearl-medical-framework' ),
            'edit_item'           => __( 'Edit Testimonials', 'pearl-medical-framework' ),
            'update_item'         => __( 'Update Testimonial', 'pearl-medical-framework' ),
            'view_item'           => __( 'View Testimonial', 'pearl-medical-framework' ),
            'search_items'        => __( 'Search Testimonial', 'pearl-medical-framework' ),
            'not_found'           => __( 'Not found', 'pearl-medical-framework' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'pearl-medical-framework' ),
        );

        $args = array(
            'label'               => __( 'Testimonials', 'pearl-medical-framework' ),
            'description'         => __( 'Testimonials', 'pearl-medical-framework' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'thumbnail', ),
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

        register_post_type( 'testimonial', apply_filters('pearl_testimonial_post_type_args', $args ) );

    }

    /**
     * Register custom columns
     *
     * @param   array   $defaults
     * @since   1.0.0
     * @return  array   $defaults
     */
    public function register_custom_column_titles ( $defaults ) {

        $new_columns = array(
            "thumb"     => __( 'Picture', 'pearl-medical-framework' ),
            // for future use
//            "text"     => __( 'Text', 'pearl-medical-framework' )
        );

        $last_columns = array();

        if ( count( $defaults ) > 2 ) {
            $last_columns = array_splice( $defaults, 2, 1 );
        }

        $defaults = array_merge( $defaults, $new_columns );
        $defaults = array_merge( $defaults, $last_columns );

        return $defaults;
    }

    /**
     * Display custom column for testimonials
     *
     * @access  public
     * @param   string $column_name
     * @since   1.0.0
     * @return  void
     */
    public function display_custom_column ( $column_name ) {
        global $post;

        switch ( $column_name ) {

            case 'thumb':
                if ( has_post_thumbnail ( $post->ID ) ) {
                    the_post_thumbnail( array( 110, 110 ) );
                } else {
                    _e ( 'No Image', 'pearl-medical-framework' );
                }
                break;
                // for future use
//            case 'text':
//                if ( get_post_meta ( $post->ID, 'PEARL_META_testimonial_text', true ) ) {
//                    echo get_post_meta ( $post->ID, 'PEARL_META_testimonial_text', true );
//                } else {
//                    _e ( 'No Text', 'pearl-medical-framework' );
//                }
                break;

            default:
                break;
        }
    }

    /**
     * Register meta boxes related to testimonial post type
     *
     * @param   array   $meta_boxes
     * @since   1.0.0
     * @return  array   $meta_boxes
     */
    public function register_meta_boxes ( $meta_boxes ) {

        $prefix = 'PEARL_META_';

        // Testimonials Meta Box
        $meta_boxes[] = array(
            'id' => 'testimonial-meta-box',
            'title' => __('Testimonial Data', 'pearl-medical-framework'),
            'pages' => array('testimonial'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name' => __('Text', 'pearl-medical-framework'),
                    'id' => "{$prefix}testimonial_text",
                    'type' => 'textarea',
                    'cols' => '20',
                    'rows' => '3'
                ),
                array(
                    'name' => __('Name', 'pearl-medical-framework'),
                    'id' => "{$prefix}patient_name",
                    'type' => 'text'
                ),
                array(
                    'name' => __('Patient', 'pearl-medical-framework'),
                    'id' => "{$prefix}the_patient",
                    'type' => 'text'
                )
            )
        );

        // apply a filter before returning meta boxes
        $meta_boxes = apply_filters( 'testimonial_meta_boxes', $meta_boxes );

        return $meta_boxes;
    }

}