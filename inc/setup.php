<?php
/**
 * Theme setup: supports, menus, image sizes, i18n.
 *
 * @owner   Session A
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'prometal_setup' );
function prometal_setup() {
	load_theme_textdomain( 'prometal', PROMETAL_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu chính (header)', 'prometal' ),
			'footer'  => __( 'Menu footer', 'prometal' ),
		)
	);

	// Kích thước ảnh — tinh chỉnh khi dựng section.
	add_image_size( 'pm-hero', 1600, 900, true );
	add_image_size( 'pm-card', 640, 420, true );
	add_image_size( 'pm-gallery', 800, 600, true );
	add_image_size( 'pm-thumb', 400, 260, true );
}
