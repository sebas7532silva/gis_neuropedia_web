<?php
/**
 * Doctor custom post type class.
 *
 * Defines the doctor post type.
 *
 * @package    Pearl_Medical_Framework
 * @subpackage Pearl_Medical_Framework/admin
 * @author     Fahid Javid <fahidjavid@icloud.com>
 */

class Pearl_Doctor_Post_Type {

    /**
     * Register doctor post type
     * @since 1.0.0
     */
    public function register_doctor_post_type() {

        $labels = array(
            'name'                => _x( 'Doctors', 'Post Type General Name', 'pearl-medical-framework' ),
            'singular_name'       => _x( 'Doctor', 'Post Type Singular Name', 'pearl-medical-framework' ),
            'menu_name'           => __( 'Doctors', 'pearl-medical-framework' ),
            'name_admin_bar'      => __( 'Doctor', 'pearl-medical-framework' ),
            'parent_item_colon'   => __( 'Parent Doctor:', 'pearl-medical-framework' ),
            'all_items'           => __( 'All Doctors', 'pearl-medical-framework' ),
            'add_new_item'        => __( 'Add New Doctor', 'pearl-medical-framework' ),
            'add_new'             => __( 'Add New', 'pearl-medical-framework' ),
            'new_item'            => __( 'New Doctor', 'pearl-medical-framework' ),
            'edit_item'           => __( 'Edit Doctors', 'pearl-medical-framework' ),
            'update_item'         => __( 'Update Doctor', 'pearl-medical-framework' ),
            'view_item'           => __( 'View Doctor', 'pearl-medical-framework' ),
            'search_items'        => __( 'Search Doctor', 'pearl-medical-framework' ),
            'not_found'           => __( 'Not found', 'pearl-medical-framework' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'pearl-medical-framework' ),
        );

        $rewrite = array(
            'slug'                => __( 'doctor', 'pearl-medical-framework' ),
            'with_front'          => true,
            'pages'               => true,
        );

        $args = array(
            'label'               => __( 'Doctors', 'pearl-medical-framework' ),
            'description'         => __( 'Doctors', 'pearl-medical-framework' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'thumbnail', 'editor' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 10,
            'menu_icon'           => 'dashicons-businessman',
            'show_in_admin_bar'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'rewrite'             => $rewrite,
            'capability_type'     => 'post',
        );

        register_post_type( 'doctor', apply_filters('pearl_doctor_post_type_args', $args ) );

    }


    /**
     * Register Doctor Department Taxonomy
     * @since 1.0.0
     */
    public function register_doctor_department_taxonomy() {

        $labels = array(
            'name'                       => _x( 'Department', 'Taxonomy General Name', 'pearl-medical-framework' ),
            'singular_name'              => _x( 'Department', 'Taxonomy Singular Name', 'pearl-medical-framework' ),
            'menu_name'                  => __( 'Departments', 'pearl-medical-framework' ),
            'all_items'                  => __( 'All Departments', 'pearl-medical-framework' ),
            'parent_item'                => __( 'Parent Department', 'pearl-medical-framework' ),
            'parent_item_colon'          => __( 'Parent Department:', 'pearl-medical-framework' ),
            'new_item_name'              => __( 'New Department Name', 'pearl-medical-framework' ),
            'add_new_item'               => __( 'Add New Department', 'pearl-medical-framework' ),
            'edit_item'                  => __( 'Edit Department', 'pearl-medical-framework' ),
            'update_item'                => __( 'Update Department', 'pearl-medical-framework' ),
            'view_item'                  => __( 'View Department', 'pearl-medical-framework' ),
            'separate_items_with_commas' => __( 'Separate Departments with commas', 'pearl-medical-framework' ),
            'add_or_remove_items'        => __( 'Add or remove Departments', 'pearl-medical-framework' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'pearl-medical-framework' ),
            'popular_items'              => __( 'Popular Departments', 'pearl-medical-framework' ),
            'search_items'               => __( 'Search Departments', 'pearl-medical-framework' ),
            'not_found'                  => __( 'Not Found', 'pearl-medical-framework' ),
        );

        $rewrite = array(
            'slug'                       => 'department',
            'with_front'                 => true,
            'hierarchical'               => true,
        );

        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => $rewrite,
        );

        register_taxonomy( 'doctor-department', array( 'doctor' ), $args );

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
     * Display custom column for doctors
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
//                if ( get_post_meta ( $post->ID, 'PEARL_META_doctor_text', true ) ) {
//                    echo get_post_meta ( $post->ID, 'PEARL_META_doctor_text', true );
//                } else {
//                    _e ( 'No Text', 'pearl-medical-framework' );
//                }
                break;

            default:
                break;
        }
    }

    /**
     * Register meta boxes related to doctor post type
     *
     * @param   array   $meta_boxes
     * @since   1.0.0
     * @return  array   $meta_boxes
     */
    public function register_meta_boxes ( $meta_boxes ) {

        $prefix = 'PEARL_META_';

        // Doctor Meta Box
        $meta_boxes[] = array(
            'id' => 'doctor-meta-box',
            'title' => __('Doctor Information', 'pearl-medical-framework'),
            'pages' => array('doctor'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name' => __('Speciality', 'pearl-medical-framework'),
                    'id' => "{$prefix}speciality",
                    'type' => 'text'
                ),
                array(
                    'name' => __('Degree', 'pearl-medical-framework'),
                    'id' => "{$prefix}degree",
                    'type' => 'text'
                ),
                array(
                    'name' => __('Experience', 'pearl-medical-framework'),
                    'id' => "{$prefix}experience",
                    'type' => 'text'
                ),
                array(
                    'name' => __('Work Days', 'pearl-medical-framework'),
                    'id' => "{$prefix}work_days",
                    'type' => 'text'
                ),
                array(
                    'name' => __('Training', 'pearl-medical-framework'),
                    'id' => "{$prefix}training",
                    'type' => 'textarea'
                ),
                array(
                    'name' => __( 'Twitter URL', 'pearl-medical-framework' ),
                    'id'   => "{$prefix}twitter_url",
                    'type' => 'url',
                ),
                array(
                    'name' => __( 'Facebook URL', 'pearl-medical-framework' ),
                    'id'   => "{$prefix}facebook_url",
                    'type' => 'url',
                ),
                array(
                    'name' => __( 'Google+ URL', 'pearl-medical-framework' ),
                    'id'   => "{$prefix}google_url",
                    'type' => 'url',
                )
            )
        );

        // apply a filter before returning meta boxes
        $meta_boxes = apply_filters( 'doctor_meta_boxes', $meta_boxes );

        return $meta_boxes;
    }

}