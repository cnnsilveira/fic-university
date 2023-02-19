<?php 
    function university_post_types () {

        // Event Post Type
        register_post_type('event', array(
            'capability_type' => 'event',
            'map_meta_cap' => true,
            'public' => true,
            'show_in_rest' => true,
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'events'
            ),
            'supports' => array('title', 'editor', 'excerpt'),
            'labels' => array(
                'name' => 'Events',
                'add_new_item' => 'Add New Event',
                'edit_item' => 'Edit Event',
                'all_items' => 'All Events',
                'singular_name' => 'Event'
            ),
            'menu_icon' => 'dashicons-calendar'
        ));

        // Program Post Type
        register_post_type('program', array(
            'public' => true,
            'show_in_rest' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'programs'),
            'supports' => array('title'),
            'labels' => array(
                'name' => 'Programs',
                'add_new_item' => 'Add New Program',
                'edit_item' => 'Edit Program',
                'all_items' => 'All Programs',
                'singular_name' => 'Program'
            ),
            'menu_icon' => 'dashicons-awards'
        ));

        // Professor Post Type
        register_post_type('professor', array(
            'show_in_rest' => true,
            'public' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'labels' => array(
                'name' => 'Professors',
                'add_new_item' => 'Add New Professor',
                'edit_item' => 'Edit Professor',
                'all_items' => 'All Professors',
                'singular_name' => 'Professor'
            ),
            'menu_icon' => 'dashicons-welcome-learn-more'
        ));

        // Campus Post Type
        register_post_type('campus', array(
            'capability_type' => 'campus',
            'map_meta_cap' => true,
            'public' => true,
            'show_in_rest' => true,
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'campuses'
            ),
            'supports' => array('title', 'editor', 'excerpt'),
            'labels' => array(
                'name' => 'Campuses',
                'add_new_item' => 'Add New Campus',
                'edit_item' => 'Edit Campus',
                'all_items' => 'All Campuses',
                'singular_name' => 'Campus'
            ),
            'menu_icon' => 'dashicons-location-alt'
        ));

        // Note Post Type
        register_post_type('note', array(
            'capability_type' => 'note',
            'map_meta_cap' => true,
            'show_in_rest' => true,
            'public' => false,
            'show_ui' => true,
            'supports' => array('title', 'editor'),
            'labels' => array(
                'name' => 'Notes',
                'add_new_item' => 'Add New Note',
                'edit_item' => 'Edit Note',
                'all_items' => 'All Notes',
                'singular_name' => 'Note'
            ),
            'menu_icon' => 'dashicons-welcome-write-blog'
        ));

        // Like Post Type
        register_post_type('like', array(
            'public' => false,
            'show_ui' => true,
            'supports' => array('title'),
            'labels' => array(
                'name' => 'Likes',
                'add_new_item' => 'Add New Like',
                'edit_item' => 'Edit Like',
                'all_items' => 'All Likes',
                'singular_name' => 'Like'
            ),
            'menu_icon' => 'dashicons-heart'
        ));

        // Slideshow Post Type
        register_post_type('slideshow', array(
            'show_in_rest' => true,
            'public' => true,
            'supports' => array('title', 'thumbnail'),
            'labels' => array(
                'name' => 'Slideshow',
                'add_new_item' => 'Add New Slide',
                'edit_item' => 'Edit Slide',
                'all_items' => 'All Slides',
                'singular_name' => 'Slide'
            ),
            'menu_icon' => 'dashicons-images-alt2'
        ));
    }

    add_action('init', 'university_post_types');
?>