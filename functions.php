<?php
/**
 * Serene theme setup and helpers.
 *
 * @package Serene
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SERENE_VERSION', '1.0.0' );

/**
 * Theme setup.
 */
function serene_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support( 'custom-logo' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'serene' ),
			'footer'  => __( 'Footer menu', 'serene' ),
		)
	);
}
add_action( 'after_setup_theme', 'serene_setup' );

/**
 * Footer widget area.
 */
function serene_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'serene' ),
			'id'            => 'footer-widgets',
			'description'   => __( 'Widgets shown in the site footer.', 'serene' ),
			'before_widget' => '<div class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'serene_widgets_init' );

/**
 * Enqueue fonts, styles and scripts.
 */
function serene_enqueue_assets() {
	wp_enqueue_style(
		'serene-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500;600&display=swap',
		array(),
		SERENE_VERSION
	);
	wp_enqueue_style( 'serene-style', get_stylesheet_uri(), array( 'serene-fonts' ), SERENE_VERSION );
	wp_enqueue_script(
		'serene-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		SERENE_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'serene_enqueue_assets' );

/**
 * Editor styles.
 */
function serene_editor_assets() {
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'serene_editor_assets' );

/**
 * Register the "serene" block pattern category.
 */
function serene_pattern_categories() {
	register_block_pattern_category(
		'serene',
		array( 'label' => __( 'Serene', 'serene' ) )
	);
}
add_action( 'init', 'serene_pattern_categories' );

/**
 * Custom block styles.
 */
function serene_block_styles() {
	register_block_style(
		'core/button',
		array(
			'name'  => 'serene-outline',
			'label' => __( 'Serene outline', 'serene' ),
		)
	);
	register_block_style(
		'core/group',
		array(
			'name'  => 'serene-card',
			'label' => __( 'Serene card', 'serene' ),
		)
	);
	register_block_style(
		'core/image',
		array(
			'name'  => 'serene-soft',
			'label' => __( 'Serene soft corners', 'serene' ),
		)
	);
	register_block_style(
		'core/quote',
		array(
			'name'  => 'serene-pull',
			'label' => __( 'Serene pull quote', 'serene' ),
		)
	);
	register_block_style(
		'core/heading',
		array(
			'name'  => 'serene-eyebrow',
			'label' => __( 'Serene eyebrow', 'serene' ),
		)
	);
}
add_action( 'init', 'serene_block_styles' );

/**
 * Shorter, calmer excerpts.
 *
 * @param int $length Default length.
 * @return int
 */
function serene_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'serene_excerpt_length' );

/**
 * Inline SVG icon helper.
 *
 * @param string $name Icon name.
 * @return string
 */
function serene_icon( $name ) {
	$icons = array(
		'leaf'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 21c0-9 7-16 16-16-1 9-8 16-16 16z"/><path d="M5 21c4-4 8-8 12-12"/></svg>',
		'lotus'  => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 20c-5 0-9-3-10-8 3 1 6 1 8 0-1-3-1-6 2-9 3 3 3 6 2 9 2 1 5 1 8 0-1 5-5 8-10 8z"/></svg>',
		'drop'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 3c4 5 7 9 7 13a7 7 0 0 1-14 0c0-4 3-8 7-13z"/></svg>',
		'stone'  => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><ellipse cx="12" cy="14" rx="8" ry="5"/><path d="M6 10c2-3 10-3 12 0"/></svg>',
		'clock'  => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
		'phone'  => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 4h4l2 5-3 2a12 12 0 0 0 5 5l2-3 5 2v4a2 2 0 0 1-2 2A17 17 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>',
	);
	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Estimated reading time for posts.
 *
 * @return string
 */
function serene_reading_time() {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) ) );
	$mins  = max( 1, (int) round( $words / 200 ) );
	return sprintf(
		/* translators: %d: minutes */
		_n( '%d min read', '%d min read', $mins, 'serene' ),
		$mins
	);
}
