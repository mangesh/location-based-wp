<?php
/**
 * Blank Theme functions and definitions
 *
 * @package Blank Theme
 */

$blank_theme = wp_get_theme();

if( ! defined( 'BLANK_THEME_VERSION' ) ) define ( 'BLANK_THEME_VERSION'  , $blank_theme->Version );
if( ! defined( 'BLANK_THEME_TEMP_URI' ) ) define ( 'BLANK_THEME_TEMP_URI' , get_template_directory_uri() );
if( ! defined( 'BLANK_THEME_TEMP_DIR' ) ) define ( 'BLANK_THEME_TEMP_DIR' , get_template_directory() );
if( ! defined( 'BLANK_THEME_CSS_URI' ) ) define ( 'BLANK_THEME_CSS_URI'  , BLANK_THEME_TEMP_URI . '/css' );
if( ! defined( 'BLANK_THEME_JS_URI' ) ) define ( 'BLANK_THEME_JS_URI'	, BLANK_THEME_TEMP_URI . '/js' 	);
if( ! defined( 'BLANK_THEME_IMG_URI' ) ) define ( 'BLANK_THEME_IMG_URI'  , BLANK_THEME_TEMP_URI . '/images' );
if( ! defined( 'BLANK_THEME_IS_DEV' ) ) define ( 'BLANK_THEME_IS_DEV'   , true );

do_action( 'blank_theme_before' );

if ( ! function_exists( 'blank_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function blank_theme_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Blank Theme, use a find and replace
	 * to change 'blank-theme' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'blank-theme', BLANK_THEME_TEMP_DIR . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'blank-theme' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'blank_theme_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	add_editor_style( array( 'editor-style.css', blank_theme_main_font_url() ) );

	do_action( 'blank_theme_after_setup_theme' );
}
endif; // blank_theme_setup
add_action( 'after_setup_theme', 'blank_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function blank_theme_content_width() {
	global $content_width;
	$content_width = apply_filters( 'blank_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'blank_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function blank_theme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'blank-theme' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget widget-sidebar large-12 medium-12 column %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'blank-theme' ),
		'id'            => 'sidebar-2',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget widget-footer large-3 column %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'blank_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function blank_theme_scripts()
{
	/*==============================
	          GOOGLE FONTS
	===============================*/

	//wp_enqueue_style( 'google-font-opensanscondensed', blank_theme_main_font_url() );
	wp_enqueue_style( 'dashicons' );

	/*==============================
	          STYLES
	===============================*/

	wp_enqueue_style( 'blank-theme-style', get_stylesheet_uri() );

	/*==============================
	          SCRIPTS
	===============================*/

	if ( BLANK_THEME_IS_DEV ) {
		wp_register_script( 'blank-theme-mmenu', BLANK_THEME_JS_URI . '/vendor/jquery.mmenu.min.all.js', array( 'jquery' ), BLANK_THEME_VERSION, true );
		wp_register_script( 'blank-theme-cycle2', BLANK_THEME_JS_URI . '/vendor/jquery.cycle2.js', array( 'jquery' ), BLANK_THEME_VERSION, true );
		wp_register_script( 'blank-theme-main', BLANK_THEME_JS_URI . '/main.js', array( 'jquery', 'blank-theme-mmenu', 'blank-theme-cycle2' ), BLANK_THEME_VERSION, true );
	} else {

		wp_register_script( 'blank-theme-main', BLANK_THEME_JS_URI . '/main.min.js', array( 'jquery' ), BLANK_THEME_VERSION, true );
	}

	wp_enqueue_script( 'blank-theme-main' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'blank_theme_scripts' );


/*==============================
          FILE INCLUDES
===============================*/

$blank_theme_depedencies = apply_filters( 'blank_theme_depedencies', array(
	BLANK_THEME_TEMP_DIR . '/inc/*.php',
	BLANK_THEME_TEMP_DIR . '/admin/*.php',
));

foreach ( $blank_theme_depedencies as $path ) {
	foreach ( glob( $path ) as $filename ) {
		include $filename;
	}
}

do_action( 'blank_theme_after' );
