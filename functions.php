<?php
/**
 * itransact Media functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package itransact_Media
 */

if ( ! function_exists( 'itransact_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function itransact_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on itransact Media, use a find and replace
		 * to change 'itransact' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'itransact', get_template_directory() . '/languages' );

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
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

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

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

	}
endif;
add_action( 'after_setup_theme', 'itransact_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function itransact_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'itransact_content_width', 640 );
}
add_action( 'after_setup_theme', 'itransact_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function itransact_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'itransact' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'itransact' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'itransact_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function itransact_scripts() {
	wp_enqueue_style( 'itransact-style', get_stylesheet_uri() );

	wp_enqueue_script( 'itransact-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'itransact-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	wp_enqueue_script( 'itransact-app', get_template_directory_uri() . '/js/app.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'itransact_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

class bootstrap_4_walker_nav_menu extends Walker_Nav_menu {

    function start_lvl( &$output, $depth ){ // ul
        $indent = str_repeat("\t", $depth); // indents the outputted HTML
        $submenu = ($depth > 0) ? ' sub-menu' : '';
        $output .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\">\n";
    }

  function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){ // li a span

    $indent = ( $depth ) ? str_repeat("\t",$depth) : '';

    $li_attributes = '';
        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
        $classes[] = ($item->current || $item->current_item_anchestor) ? 'active' : '';
        $classes[] = 'nav-item';
        $classes[] = 'nav-item-' . $item->ID;
        if( $depth && $args->walker->has_children ){
            $classes[] = 'dropdown-menu';
        }

        $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = ' class="' . esc_attr($class_names) . '"';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'.$item->ID, $item, $args);
        $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr($item->url) . '"' : '';

        $attributes .= ( $args->walker->has_children ) ? ' class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="nav-link"';

        $item_output = $args->before;
        $item_output .= ( $depth > 0 ) ? '<a class="dropdown-item"' . $attributes . '>' : '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters ( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

    }

}
register_nav_menu('navbar', __('Navbar', 'itransact'));
register_nav_menu('legal', __('Legal Menu', 'itransact'));
register_nav_menu('header', __('Header Menu', 'itransact'));

function footer_widgets_init() {

	register_sidebar( array(
		'name'          => 'Footer',
		'id'            => 'footer_widgets',
		'before_widget' => '<div class="col-md">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'footer_widgets_init' );



function ct_itransact_social_array() {

	$social_sites = array(
		'linkedin'      => 'itransact_linkedin_profile',
		'twitter'       => 'itransact_twitter_profile',
		'facebook'      => 'itransact_facebook_profile',
		'instagram'   	=> 'itransact_instagram_profile',
	);

	return apply_filters( 'ct_itransact_social_array_filter', $social_sites );
}
function my_add_customizer_sections( $wp_customize ) {

	$social_sites = ct_itransact_social_array();

	// set a priority used to order the social sites
	$priority = 5;

	// section
	$wp_customize->add_section( 'ct_itransact_social_media_icons', array(
		'title'       => __( 'Social Media', 'itransact' ),
		'priority'    => 25,
		'description' => __( 'Add the URL for each of your social profiles.', 'itransact' )
	) );

	// create a setting and control for each social site
	foreach ( $social_sites as $social_site => $value ) {

		$label = ucfirst( $social_site );

		if ( $social_site == 'google-plus' ) {
			$label = 'Google Plus';
		} elseif ( $social_site == 'linkedin' ) {
			$label = 'LinkedIn';
		} elseif ( $social_site == 'facebook' ) {
			$label = 'Facebook';
		} elseif ( $social_site == 'twitter' ) {
			$label = 'Twitter';
		} elseif ( $social_site == 'whatsapp' ) {
			$label = 'WhatsApp';
		} elseif ( $social_site == 'wechat' ) {
			$label = 'WeChat';
		}
		// setting
		$wp_customize->add_setting( $social_site, array(
			'sanitize_callback' => 'esc_url_raw'
		) );
		// control
		$wp_customize->add_control( $social_site, array(
			'type'     => 'url',
			'label'    => $label,
			'section'  => 'ct_itransact_social_media_icons',
			'priority' => $priority
		) );
		// increment the priority for next site
		$priority = $priority + 5;
	}
}
add_action( 'customize_register', 'my_add_customizer_sections' );

function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
         	background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2aWV3Qm94PSIwIDAgMTE1NS41MiAzNDAuODEiPjxkZWZzPjxzdHlsZT4uYXtmaWxsOnVybCgjYSk7fS5ie2ZpbGw6dXJsKCNiKTt9LmN7ZmlsbDp1cmwoI2MpO30uZHtmaWxsOnVybCgjZCk7fS5le2ZpbGw6IzIyMmEzMTt9LmZ7ZmlsbDp1cmwoI2UpO308L3N0eWxlPjxyYWRpYWxHcmFkaWVudCBpZD0iYSIgY3g9IjEyNi45MyIgY3k9IjI2OS4wOSIgcj0iNDQwLjU0IiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHN0b3Agb2Zmc2V0PSIwIiBzdG9wLWNvbG9yPSIjZjRlNjAwIi8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjZjE4NTE5Ii8+PC9yYWRpYWxHcmFkaWVudD48cmFkaWFsR3JhZGllbnQgaWQ9ImIiIGN4PSItNTMuMTIiIGN5PSIzNC45NyIgcj0iMjgwLjA0IiB4bGluazpocmVmPSIjYSIvPjxyYWRpYWxHcmFkaWVudCBpZD0iYyIgY3g9IjI2OS4zNCIgY3k9IjM4OS4xMSIgcj0iMTk3LjI0IiB4bGluazpocmVmPSIjYSIvPjxyYWRpYWxHcmFkaWVudCBpZD0iZCIgY3g9IjExOC42NCIgY3k9IjM3OS45MyIgcj0iMjMwLjQ2IiB4bGluazpocmVmPSIjYSIvPjxyYWRpYWxHcmFkaWVudCBpZD0iZSIgY3g9IjExOC42MiIgY3k9IjM4MC4yMiIgcj0iMjMwLjU5IiB4bGluazpocmVmPSIjYSIvPjwvZGVmcz48dGl0bGU+aXRyPC90aXRsZT48cGF0aCBjbGFzcz0iYSIgZD0iTTEzOC4zNCwzNTBsLTMuNDcsNy45My4zLDI3Mi43MSwxOTAuMjctNDguNzNWMzczLjkxWk0zMDguNDMsNTY2LjI0bC00NC40OSw5Ljk0LS4zNC0xMTYuODNMMzA4LjQzLDQ2MVptLTIyLjI0LTEyMS4xYy0xMi40MiwwLTIyLjQ4LTExLjc0LTIyLjQ4LTI2LjIzczEwLjA2LTI2LjIzLDIyLjQ4LTI2LjIzLDIyLjQ4LDExLjc1LDIyLjQ4LDI2LjIzUzI5OC42LDQ0NS4xNCwyODYuMTksNDQ1LjE0WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTgxLjI2IC0yODkuNzgpIi8+PHBvbHlnb24gY2xhc3M9ImIiIHBvaW50cz0iMzIuNjUgNzQuNDQgMCA3NC40NCAwIDMxNS4wNCA1My45MSAzNDAuODEgNTcuMDggNjAuMTcgMzIuNjUgNzQuNDQiLz48cGF0aCBjbGFzcz0iYyIgZD0iTTE2OC41NCwzNTMuODJsMjguNTcsMy42NmMxLjE1LTIzLjI4LDE2LjQ4LTQxLjgyLDM1LjE2LTQxLjgyLDE5LjQyLDAsMzUuMjMsMjAsMzUuMjMsNDQuNTh2Ni4yNWwyMC4xNywyLjU4YzAtLjcsMC02LDAtOC44MywwLTM4Ljg1LTIxLjY2LTcwLjQ2LTU1LjM2LTcwLjQ2QzE5Ny42NCwyODkuNzgsMTcxLjM3LDMxOCwxNjguNTQsMzUzLjgyWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTgxLjI2IC0yODkuNzgpIi8+PHBhdGggY2xhc3M9ImQiIGQ9Ik0yNDUuODcsMzYzLjcycS4wNi0xLjczLjA2LTMuNDhhOTQuNTUsOTQuNTUsMCwwLDAtMy44OS0yNy4zLDE2LjE5LDE2LjE5LDAsMCwwLTkuNzctMy41M2MtNC42OCwwLTkuMDUsMi4zMS0xMi42Miw2LjExYTUyLjg5LDUyLjg5LDAsMCwxLDYuMTUsMjQuNzJjMCwuMzEsMCwuNiwwLC45MVoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC04MS4yNiAtMjg5Ljc4KSIvPjxwYXRoIGNsYXNzPSJlIiBkPSJNNDMyLDU2Ny4wOGMtOS42Myw1LjQyLTI0LjI3LDktMzYuOTEsOS0yNy4yOSwwLTQyLjczLTE0LjY0LTQyLjczLTQ2Ljk0VjQ5NC4yNkgzMzUuOXYtMzIuNWgxNi44NVY0MjIuMjRoMzkuMTJ2MzkuNTJoMzMuN3YzMi41aC0zMy43djI5LjI5YzAsMTIuMjQsNSwxNi42NSwxMywxNi42NSw3LjIyLDAsMTIuMjQtMi40MSwxNy00LjYxWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTgxLjI2IC0yODkuNzgpIi8+PHBhdGggY2xhc3M9ImUiIGQ9Ik00NDEuMjMsNDYxLjc2aDM3LjkyVjQ3NWM3LjQyLTExLjIzLDE4LjI1LTE2LjI1LDI5LjA5LTE2LjI1QTM3LjU2LDM3LjU2LDAsMCwxLDUyMi40OCw0NjJsLTMuMjEsMzcuNTJhNDYuNDQsNDYuNDQsMCwwLDAtMTUuNDUtMi44MWMtMTMuMjQsMC0yMy4yNyw3LjQyLTIzLjI3LDMxLjA5VjU3My4zSDQ0MS4yM1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC04MS4yNiAtMjg5Ljc4KSIvPjxwYXRoIGNsYXNzPSJlIiBkPSJNNjQ5LjQ3LDQ2MS43NlY1NzMuM0g2MTIuMzV2LTcuODJjLTcuODIsNi4yMi0xNy44NSwxMC42My0zMS4yOSwxMC42My0zMi4zLDAtNTQuMzctMjYuMDgtNTQuMzctNTksMC0zMy41LDIzLjA3LTU4LjE4LDU0LjM3LTU4LjE4LDEzLjI0LDAsMjMuNDcsNC40MiwzMS4yOSwxMXYtOC4yM1ptLTM3LjkyLDU1LjU3YTIyLjY1LDIyLjY1LDAsMCwwLTIyLjg3LTIyLjQ3QTIyLjM1LDIyLjM1LDAsMCwwLDU2Niw1MTcuMzNhMjIuNzcsMjIuNzcsMCwxLDAsNDUuNTQsMFoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC04MS4yNiAtMjg5Ljc4KSIvPjxwYXRoIGNsYXNzPSJlIiBkPSJNNjYyLDQ2MS43NmgzOC41MnYxNS40NWM0LjYxLTksMTcuMjUtMTguNDYsMzQuOS0xOC40NiwyMS42NywwLDQyLjczLDE0LjY1LDQyLjczLDQ1Ljk0VjU3My4zSDczOC42M1Y1MTQuOTJjMC0xMi44My02LjQyLTIwLjI2LTE3LjQ1LTIwLjI2LTExLjY0LDAtMTkuODYsOC40My0xOS44NiwyMS40N1Y1NzMuM0g2NjJaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtODEuMjYgLTI4OS43OCkiLz48cGF0aCBjbGFzcz0iZSIgZD0iTTc4NS42Myw1NTguNDZsMTYuNDUtMjcuMDhjOS40Myw2LjQxLDIxLjQ2LDEzLDM0LjcsMTMsNSwwLDEwLTEuMiwxMC01LjIxLDAtMS4yLS42MS0yLjYxLTUtNC4yMS01LjIyLTEuODEtMjEuMjctNi40Mi0yNy42OS05LjIzLTEzLjQ0LTUuODItMjEuODYtMTUuMjUtMjEuODYtMzEuMSwwLTIxLjY2LDE5LjA2LTM2LjMxLDQ4LjM1LTM2LjMxLDE1LDAsMjguNjgsMy40MSw0My4xMywxMy44NGwtMTYuMDUsMjYuMjhjLTEwLjgzLTYuNDItMjEuMjctOC44Mi0yNy40OS04LjgyLTQuODEsMC04LjgyLDEuNi04LjgyLDQuNjFzMi40LDUsMTAuNDMsNi44MmExMTYuMzYsMTE2LjM2LDAsMCwxLDI2LjA4LDljMTMuODQsNy4yMiwxOC44NiwxNS44NSwxOC44NiwyOC40OSwwLDIxLjg2LTE4LjI2LDM3LjkxLTQ5LjU1LDM3LjkxQTgwLjc5LDgwLjc5LDAsMCwxLDc4NS42Myw1NTguNDZaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtODEuMjYgLTI4OS43OCkiLz48cGF0aCBjbGFzcz0iZSIgZD0iTTEwMTMuNiw0NjEuNzZWNTczLjNIOTc2LjQ4di03LjgyYy03LjgyLDYuMjItMTcuODUsMTAuNjMtMzEuMjksMTAuNjMtMzIuMywwLTU0LjM3LTI2LjA4LTU0LjM3LTU5LDAtMzMuNSwyMy4wNy01OC4xOCw1NC4zNy01OC4xOCwxMy4yNCwwLDIzLjQ3LDQuNDIsMzEuMjksMTF2LTguMjNabS0zNy45Miw1NS41N2EyMi42NSwyMi42NSwwLDAsMC0yMi44Ny0yMi40NywyMi4zNSwyMi4zNSwwLDAsMC0yMi42NywyMi40NywyMi43NywyMi43NywwLDEsMCw0NS41NCwwWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTgxLjI2IC0yODkuNzgpIi8+PHBhdGggY2xhc3M9ImUiIGQ9Ik0xMDIxLjUxLDUxNy4zM2MwLTMzLjksMjQuMDgtNTguMzgsNTkuNzktNTguMzgsMjIuNDYsMCw0My41MywxMC4yMyw1My43NiwzMy4zMWwtMzEuMywxNi4yNWMtNC44MS04LTEyLjIzLTEzLTIxLjQ2LTEzLTEyLjg0LDAtMjEuODcsOS40Mi0yMS44NywyMS44NiwwLDEyLjg0LDkuMjMsMjIuMjcsMjEuNjcsMjIuMjcsOS40MywwLDE3Ljg1LTUuMjIsMjEuMjYtMTNsMzEuNSwxOC42NmMtOS44MywxOC44NS0yOS40OSwzMC40OS01NCwzMC40OUMxMDQ1LjU5LDU3NS43MSwxMDIxLjUxLDU1MS40NCwxMDIxLjUxLDUxNy4zM1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC04MS4yNiAtMjg5Ljc4KSIvPjxwYXRoIGNsYXNzPSJlIiBkPSJNMTIzNi43OSw1NjcuMDhjLTkuNjMsNS40Mi0yNC4yOCw5LTM2LjkyLDktMjcuMjgsMC00Mi43My0xNC42NC00Mi43My00Ni45NFY0OTQuMjZoLTE2LjQ1di0zMi41aDE2Ljg1VjQyMi4yNGgzOS4xMnYzOS41MmgzMy43MXYzMi41aC0zMy43MXYyOS4yOWMwLDEyLjI0LDUsMTYuNjUsMTMsMTYuNjUsNy4yMywwLDEyLjI0LTIuNDEsMTctNC42MVoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC04MS4yNiAtMjg5Ljc4KSIvPjxwYXRoIGNsYXNzPSJmIiBkPSJNMTI2LjY1LDM1Ni41OGwwLC4yMUwxMzguMzQsMzUwLDE1NC41MSwzNTJjLjI1LTEuNjcuNTQtMy4zMi45Mi00LjkyLDMuMzYtMjQuMTQsMTUuODUtNDQuNiwzMy42OC01Ny4yOEMxNTEuNjYsMjkwLjU3LDEyOC4xMiwzMTkuOSwxMjYuNjUsMzU2LjU4WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTgxLjI2IC0yODkuNzgpIi8+PC9zdmc+);
					height:70px;
					width:320px;
					background-size: 320px 85px;
					background-repeat: no-repeat;
        	padding-bottom: 20px;
				}
				input#wp-submit {
						background: linear-gradient(to bottom right, #fbde25, #f9a520);
						border: 1px solid #f9af20;
						color: #fff;
						text-shadow: none;
						box-shadow: none;
						padding: 0 1.5rem;
						border-radius: 99px;
				}
				input[type=text]:focus, input[type=password]:focus {
						border: 1px solid #f9af20 !important;
						box-shadow: 0 0 2px rgba(249,175,32,.8) !important;
				}
				.login #login_error, .login .message, .login .success {
							border-left: 4px solid #f9af20 !important;
				}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );
