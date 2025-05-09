<?php
/**
 * Gallery Item custom post type class.
 *
 * Defines the gallery item post type.
 *
 * @package    Pearl_Medical_Framework
 * @subpackage Pearl_Medical_Framework/admin
 * @author     Fahid Javid <fahidjavid@icloud.com>
 */

class Pearl_Gallery_Post_Type {

    /**
     * Register gallery item post type
     * @since 1.0.0
     */
    public function Pearl_Gallery_Post_Type() {

        $labels = array(
            'name'                => _x( 'Gallery Items', 'Post Type General Name', 'pearl-medical-framework' ),
            'singular_name'       => _x( 'Gallery Item', 'Post Type Singular Name', 'pearl-medical-framework' ),
            'menu_name'           => __( 'Gallery Items', 'pearl-medical-framework' ),
            'name_admin_bar'      => __( 'Gallery Item', 'pearl-medical-framework' ),
            'parent_item_colon'   => __( 'Parent Gallery Item:', 'pearl-medical-framework' ),
            'all_items'           => __( 'All Gallery Items', 'pearl-medical-framework' ),
            'add_new_item'        => __( 'Add New Gallery Item', 'pearl-medical-framework' ),
            'add_new'             => __( 'Add New', 'pearl-medical-framework' ),
            'new_item'            => __( 'New Gallery Item', 'pearl-medical-framework' ),
            'edit_item'           => __( 'Edit Gallery Items', 'pearl-medical-framework' ),
            'update_item'         => __( 'Update Gallery Item', 'pearl-medical-framework' ),
            'view_item'           => __( 'View Gallery Item', 'pearl-medical-framework' ),
            'search_items'        => __( 'Search Gallery Item', 'pearl-medical-framework' ),
            'not_found'           => __( 'Not found', 'pearl-medical-framework' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'pearl-medical-framework' ),
        );

        $rewrite = array(
            'slug'                => 'gallery-item',
            'with_front'          => true,
            'pages'               => true,
            'feeds'               => false,
        );

        $args = array(
            'label'               => __( 'Gallery Items', 'pearl-medical-framework' ),
            'description'         => __( 'Gallery Items', 'pearl-medical-framework' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'thumbnail' ),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 10,
            'menu_icon'           => 'dashicons-format-gallery',
            'show_in_admin_bar'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'rewrite'             => false,
            'capability_type'     => 'post',
        );

        register_post_type( 'gallery', apply_filters('pearl_gallery_post_type_args', $args ) );

    }


    /**
     * Register Gallery Type Taxonomy
     * @since 1.0.0
     */
    public function register_gallery_type_taxonomy() {

        $labels = array(
            'name'                       => _x( 'Type', 'Taxonomy General Name', 'pearl-medical-framework' ),
            'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'pearl-medical-framework' ),
            'menu_name'                  => __( 'Types', 'pearl-medical-framework' ),
            'all_items'                  => __( 'All Types', 'pearl-medical-framework' ),
            'parent_item'                => __( 'Parent Type', 'pearl-medical-framework' ),
            'parent_item_colon'          => __( 'Parent Type:', 'pearl-medical-framework' ),
            'new_item_name'              => __( 'New Type Name', 'pearl-medical-framework' ),
            'add_new_item'               => __( 'Add New Type', 'pearl-medical-framework' ),
            'edit_item'                  => __( 'Edit Type', 'pearl-medical-framework' ),
            'update_item'                => __( 'Update Type', 'pearl-medical-framework' ),
            'view_item'                  => __( 'View Type', 'pearl-medical-framework' ),
            'separate_items_with_commas' => __( 'Separate Types with commas', 'pearl-medical-framework' ),
            'add_or_remove_items'        => __( 'Add or remove Types', 'pearl-medical-framework' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'pearl-medical-framework' ),
            'popular_items'              => __( 'Popular Types', 'pearl-medical-framework' ),
            'search_items'               => __( 'Search Types', 'pearl-medical-framework' ),
            'not_found'                  => __( 'Not Found', 'pearl-medical-framework' ),
        );

        $rewrite = array(
            'slug'                       => 'gallery-item-type',
            'with_front'                 => true,
            'hierarchical'               => false,
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

        register_taxonomy( 'gallery-type', array( 'gallery' ), $args );

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
            "thumb"     => __( 'Thumbnail', 'pearl-medical-framework' ),
            "text"     => __( 'Description', 'pearl-medical-framework' )
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
     * Display custom column for gallery items
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
            case 'text':
                if ( get_post_meta ( $post->ID, 'PEARL_META_gallery_desc', true ) ) {
                    echo get_post_meta ( $post->ID, 'PEARL_META_gallery_desc', true );
                } else {
                    _e ( 'No Text', 'pearl-medical-framework' );
                }
                break;
        }
    }

    /**
     * Register meta boxes related to gallery item post type
     *
     * @param   array   $meta_boxes
     * @since   1.0.0
     * @return  array   $meta_boxes
     */
    public function register_meta_boxes ( $meta_boxes ) {

        $prefix = 'PEARL_META_';

        // Gallery Items Meta Box
        $meta_boxes[] = array(
            'id' => 'gallery-meta-box',
            'title' => __('Gallery Item Data', 'pearl-medical-framework'),
            'pages' => array('gallery'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name' => __('Short Description', 'pearl-medical-framework'),
                    'desc' => __('Provide short description to display on the gallery post.', 'pearl-medical-framework'),
                    'id' => "{$prefix}gallery_desc",
                    'type' => 'text',
                ),
                array(
                    'name' => __('Video URL', 'pearl-medical-framework'),
                    'desc' => __('(optional) Provide a video url for this gallery post. e.g: https://vimeo.com/7449107', 'pearl-medical-framework'),
                    'id' => "{$prefix}gallery_video",
                    'type' => 'text',
                ),
            )
        );

        // apply a filter before returning meta boxes
        $meta_boxes = apply_filters( 'gallery_meta_boxes', $meta_boxes );

        return $meta_boxes;
    }

}