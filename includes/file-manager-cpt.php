<?php

/**
* Set up Custom Post Type "workplaces"
* Nothing significant here.
*/
function create_workplace_post_type() {
    $labels = array(
        'name'               => _x( 'workplaces', 'Plural Name' ),
        'singular_name'      => _x( 'workplace', 'Singular Name' ),
        'add_new'            => _x( 'Add New', 'workplace' ),
        'add_new_item'       => __( 'Add New workplace' ),
        'edit_item'          => __( 'Edit workplace' ),
        'new_item'           => __( 'New workplace' ),
        'all_items'          => __( 'All workplaces' ),
        'view_item'          => __( 'View workplace' ),
        'search_items'       => __( 'Search workplaces' ),
        'not_found'          => __( 'No workplaces found' ),
        'not_found_in_trash' => __( 'No workplaces found in the Trash' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'workplaces',
    );
    $args = array(
        'label'                 => __( 'workplace', 'McPlayer' ),
        'description'           => __( 'workplace', 'McPlayer' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'page-attributes', 'comments' ),
        'taxonomies'             => false,
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => false,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,		
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page'
    );
    register_post_type( 'workplace', $args );
}
add_action( 'init', 'create_workplace_post_type' );