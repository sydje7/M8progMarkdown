<?php
function m8prog_enqueue_styles() {
	wp_enqueue_style(
		'm8prog-style',
		get_stylesheet_uri()
	);
}
add_action( 'wp_enqueue_scripts', 'm8prog_enqueue_styles' );