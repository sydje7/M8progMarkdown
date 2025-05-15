<?php 
function register_recepten_cpt() {
    register_post_type('recept',
        array(
            'labels' => array(
                'name' => __('Recepten'),
                'singular_name' => __('Recept')
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'recepten'),
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array('ingredient')
        )
    );
}
add_action('init', 'register_recepten_cpt');

function register_ingredient_taxonomy() {
    register_taxonomy(
        'ingredient',
        'recept',
        array(
            'label' => __('Ingrediënten'),
            'rewrite' => array('slug' => 'ingredient'),
            'hierarchical' => false,
        )
    );
}
add_action('init', 'register_ingredient_taxonomy');