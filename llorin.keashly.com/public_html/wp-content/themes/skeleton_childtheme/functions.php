<?php
/**
 * Skeleton theme functions and definitions
 *
 * This theme is largely based on skeleton with some significant modifications
 * mainly to template files, but adds additional helper functions to the layout in general.
 * Other functions are attached to action and filter hooks in WordPress to change core functionality.
 * 
 * Layout Functions:
 * 
 * st_header  // Opening header tag and logo/header text
 * st_header_extras // Additional content may be added to the header
 * st_navbar // Opening navigation element and WP3 menus
 * st_before_content // Opening content wrapper 
 * st_after_content // Closing content wrapper 
 * st_before_sidebar // Opening sidebar wrapper 
 * st_after_sidebar // Closing sidebar wrapper 
 * st_before_footer // Opening footer wrapper 
 * st_footer // The footer (includes sidebar-footer.php)
 * st_after_footer // The closing footer wrapper 
 * 
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, skeleton_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'skeleton_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage skeleton
 * @since skeleton 0.1
 */


/*-----------------------------------------------------------------------------------*/
/* Initialize the Options Framework
/* http://wptheming.com/options-framework-theme/
/*-----------------------------------------------------------------------------------*/




if ( !function_exists( 'optionsframework_init' ) ) {

define('OPTIONS_FRAMEWORK_URL', TEMPLATEPATH . '/admin/');
define('OPTIONS_FRAMEWORK_DIRECTORY', get_bloginfo('template_directory') . '/admin/');

require_once (OPTIONS_FRAMEWORK_URL . 'options-framework.php');

}

if ( class_exists( 'jigoshop' ) ) {
require_once (STYLESHEETPATH . '/jigoshop_functions.php');
}


require_once (TEMPLATEPATH . '/shortcodes.php');

/* 
 * This is an example of how to add custom scripts to the options panel.
 * This one shows/hides the an option when a checkbox is clicked.
 */
add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

if (!function_exists('optionsframework_custom_scripts')) {

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#use_logo_image').click(function() {
		jQuery('#section-header_logo,#section-logo_width,#section-logo_height').fadeToggle(400);
	});
	
	if (jQuery('#use_logo_image:checked').val() !== undefined) {
		jQuery('#section-header_logo,#section-logo_width,#section-logo_height').show();
	}
	
});
</script>

<?php
}
}
// Register Core Stylesheets
// These are necessary for the theme to function as intended
// Supports the 'Better WordPress Minify' plugin to properly minimize styleshsets into one.
// http://wordpress.org/extend/plugins/bwp-minify/

if ( !function_exists( 'st_registerstyles' ) ) {

add_action('get_header', 'st_registerstyles');
function st_registerstyles() {
	$theme  = get_theme( get_current_theme());
	$version = $theme['Version'];
  	$stylesheets = wp_enqueue_style('skeleton', get_bloginfo('template_directory').'/skeleton.css', false, $version, 'screen, projection');
    $stylesheets .= wp_enqueue_style('theme', get_bloginfo('stylesheet_directory').'/style.css', 'skeleton', $version, 'screen, projection');
  	$stylesheets .= wp_enqueue_style('layout', get_bloginfo('template_directory').'/layout.css', 'theme', $version, 'screen, projection');
    $stylesheets .= wp_enqueue_style('formalize', get_bloginfo('template_directory').'/formalize.css', 'theme', $version, 'screen, projection');
    $stylesheets .= wp_enqueue_style('superfish', get_bloginfo('template_directory').'/superfish.css', 'theme', $version, 'screen, projection');
		if ( class_exists( 'jigoshop' ) ) {
	  $stylesheets .= wp_enqueue_style('jigoshop', get_bloginfo('template_directory').'/jigoshop.css', 'theme', $version, 'screen, projection');
		}
		echo apply_filters ('child_add_stylesheets',$stylesheets);
}

}

// Build Query vars for dynamic theme option CSS from Options Framework

if ( !function_exists( 'production_stylesheet' )) {

function production_stylesheet($public_query_vars) {
    $public_query_vars[] = 'get_styles';
    return $public_query_vars;
}
add_filter('query_vars', 'production_stylesheet');
}

if ( !function_exists( 'theme_css' ) ) {

add_action('template_redirect', 'theme_css');
function theme_css(){
    $css = get_query_var('get_styles');
    if ($css == 'css'){
        include_once (TEMPLATEPATH . '/style.php');
        exit;  //This stops WP from loading any further
    }
}

}

if ( !function_exists( 'st_header_scripts' ) ) {

add_action('init', 'st_header_scripts');
function st_header_scripts() {
  $javascripts  = wp_enqueue_script('jquery');
  $javascripts .= wp_enqueue_script('custom',get_bloginfo('template_url') ."/javascripts/app.js",array('jquery'),'1.2.3',true);
	$javascripts .= wp_enqueue_script('superfish',get_bloginfo('template_url') ."/javascripts/superfish.js",array('jquery'),'1.2.3',true);
	$javascripts .= wp_enqueue_script('formalize',get_bloginfo('template_url') ."/javascripts/jquery.formalize.min.js",array('jquery'),'1.2.3',true);
	echo apply_filters ('child_add_javascripts',$javascripts);
}

}



/** Tell WordPress to run skeleton_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'skeleton_setup' );

if ( ! function_exists( 'skeleton_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override skeleton_setup() in a child theme, add your own skeleton_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Skeleton 1.0
 */
function skeleton_setup() {
	
	if ( class_exists( 'bbPress' ) ) {
	add_theme_support( 'bbpress' );
	}
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
	// add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	
	// Register the available menus
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'skeleton' ),
	));

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'skeleton', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );


		// No support for text inside the header image.
		if ( ! defined( 'NO_HEADER_TEXT' ) )
			define( 'NO_HEADER_TEXT', true );
			
		if ( ! defined( 'HEADER_IMAGE_WIDTH') )
			define( 'HEADER_IMAGE_WIDTH', apply_filters( 'skeleton_header_image_width',960));
			
			
		if ( ! defined( 'HEADER_IMAGE_HEIGHT') )
			define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'skeleton_header_image_height',185 ));

		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See skeleton_admin_header_style(), below.
		add_custom_image_header( '', 'skeleton_admin_header_style' );

		// ... and thus ends the changeable header business.

		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers( array(
			'berries' => array(
				'url' => '%s/images/headers/berries.jpg',
				'thumbnail_url' => '%s/images/headers/berries-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Berries', 'skeleton' )
			),
			'cherryblossom' => array(
				'url' => '%s/images/headers/cherryblossoms.jpg',
				'thumbnail_url' => '%s/images/headers/cherryblossoms-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Cherry Blossoms', 'skeleton' )
			),
			'concave' => array(
				'url' => '%s/images/headers/concave.jpg',
				'thumbnail_url' => '%s/images/headers/concave-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Concave', 'skeleton' )
			),
			'fern' => array(
				'url' => '%s/images/headers/fern.jpg',
				'thumbnail_url' => '%s/images/headers/fern-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Fern', 'skeleton' )
			),
			'forestfloor' => array(
				'url' => '%s/images/headers/forestfloor.jpg',
				'thumbnail_url' => '%s/images/headers/forestfloor-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Forest Floor', 'skeleton' )
			),
			'inkwell' => array(
				'url' => '%s/images/headers/inkwell.jpg',
				'thumbnail_url' => '%s/images/headers/inkwell-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Inkwell', 'skeleton' )
			),
			'path' => array(
				'url' => '%s/images/headers/path.jpg',
				'thumbnail_url' => '%s/images/headers/path-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Path', 'skeleton' )
			),
			'sunset' => array(
				'url' => '%s/images/headers/sunset.jpg',
				'thumbnail_url' => '%s/images/headers/sunset-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Sunset', 'skeleton' )
			)
		) );
	}
	endif;

	/**
	 * Styles the header image displayed on the Appearance > Header admin panel.
	 *
	 * Referenced via add_custom_image_header() in skeleton_setup().
	 *
	 * @since Skeleton 1.0
	 */
	if ( !function_exists( 'skeleton_admin_header_style' ) ) :

	function skeleton_admin_header_style() {
	?>
	<style type="text/css">
	/* Shows the same border as on front end */
	#headimg {
		border-bottom: 100px solid #000;
		border-top: 4px solid #000;
	}
	/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
		#headimg #name { }
		#headimg #desc { }
	*/
	</style>
	<?php
	}
	endif;

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Skeleton 1.0
 * @return int
 */
if ( !function_exists( 'skeleton_excerpt_length' ) ) {

function skeleton_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'skeleton_excerpt_length' );

}
/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Skeleton 1.0
 * @return string "Continue Reading" link
 */

if ( !function_exists( 'skeleton_continue_reading_link' ) ) {

function skeleton_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'skeleton' ) . '</a>';
}
}
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and skeleton_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Skeleton 1.0
 * @return string An ellipsis
 */

if ( !function_exists( 'skeleton_auto_excerpt_more' ) ) {

function skeleton_auto_excerpt_more( $more ) {
	return ' &hellip;' . skeleton_continue_reading_link();
}
add_filter( 'excerpt_more', 'skeleton_auto_excerpt_more' );

}
/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Skeleton 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
if ( !function_exists( 'skeleton_custom_excerpt_more' ) ) {

function skeleton_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= skeleton_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'skeleton_custom_excerpt_more' );

}
/**
 * Removes inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Skeleton's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 * @since Skeleton 1.2
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override st_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @uses register_sidebar
 */
//

if ( !function_exists( 'remove_more_jump_link' ) ) {

function remove_more_jump_link($link) { 
	$offset = strpos($link, '#more-');
	if ($offset) {
	$end = strpos($link, '"',$offset);
	}
	if ($end) {
	$link = substr_replace($link, '', $offset, $end-$offset);
	}
	return $link;
	}
	add_filter('the_content_more_link', 'remove_more_jump_link');

}

if ( !function_exists( 'st_widgets_init' ) ) {

function st_widgets_init() {
		// Area 1, located at the top of the sidebar.
		register_sidebar( array(
		'name' => __( 'Posts Widget Area', 'skeleton' ),
		'id' => 'primary-widget-area',
		'description' => __( 'Shown only in Blog Posts, Archives, Categories, etc.', 'skeleton' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );


	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Pages Widget Area', 'skeleton' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'Shown only in Pages', 'skeleton' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'skeleton' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'skeleton' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'skeleton' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'skeleton' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	// Register bbPress sidebar if plugin is installed
	if ( class_exists( 'bbPress' ) ) {
	register_sidebar( array(
		'name' => __( 'Forum Sidebar', 'skeleton' ),
		'id' => 'bbpress-widget-area',
		'description' => __( 'Sidebar displayed in forum', 'skeleton' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	}
	
	// Register Jigoshop Cart sidebar if plugin is installed
	if ( class_exists( 'jigoshop' ) ) {
	register_sidebar( array(
		'name' => __( 'Jigoshop Sidebar', 'skeleton' ),
		'id' => 'shop-widget-area',
		'description' => __( 'Sidebar displayed in Jigoshop pages', 'skeleton' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	}
	
}
/** Register sidebars by running skeleton_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'st_widgets_init' );

}

/** Comment Styles */

if ( ! function_exists( 'st_comments' ) ) :
function st_comments($comment, $args, $depth) {
$GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
			<div id="comment-<?php comment_ID(); ?>" class="single-comment clearfix">
				<div class="comment-author vcard"> <?php echo get_avatar($comment,$size='64',$default='<path_to_url>' ); ?></div>
				<div class="comment-meta commentmetadata">
						<?php if ($comment->comment_approved == '0') : ?>
						<em><?php _e('Comment is awaiting moderation','smpl');?></em> <br />
						<?php endif; ?>
						<h6><?php echo __('By','smpl').' '.get_comment_author_link(). ' '. get_comment_date(). '  -  ' . get_comment_time(); ?></h6>
						<?php comment_text() ?>
						<?php edit_comment_link(__('Edit comment','smpl'),'  ',''); ?>
						<?php comment_reply_link(array_merge( $args, array('reply_text' => __('Reply','smpl'),'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
				</div>
		</div>
<!-- </li> -->
<?php  }
endif;

if ( ! function_exists( 'skeleton_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Skeleton 1.0
 */
function skeleton_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'skeleton' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'skeleton' ), get_the_author() ),
			get_the_author()
		)
	);
}

endif;

if ( ! function_exists( 'skeleton_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Skeleton 1.0
 */
function skeleton_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'skeleton' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'skeleton' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'skeleton' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}

endif;


// Header Functions

// Hook to add content before header

if ( !function_exists( 'st_above_header' ) ) {

function st_above_header() {
    do_action('st_above_header');
}

} // endif

// Primary Header Function

if ( !function_exists( 'st_header' ) ) {

function st_header() {
  do_action('st_header');
}

}


// Opening #header div with flexible grid

if ( !function_exists( 'st_header_open' ) ) {

function st_header_open() {
  echo "<div id=\"header\" class=\"sixteen columns\">\n<div class=\"inner\">\n";
}
} // endif

add_action('st_header','st_header_open', 1);


// Hookable theme option field to add add'l content to header
// Child Theme Override: child_header_extras();

if ( !function_exists( 'st_header_extras' ) ) {

function st_header_extras() {
	if (of_get_option('header_extra')) {
		$extras  = "<div class=\"header_extras\">";
		$extras .= of_get_option('header_extra');
		$extras .= "</div>";
		echo apply_filters ('child_header_extras',$extras);
	}
}
} // endif

add_action('st_header','st_header_extras', 2);


// Build the logo
// Child Theme Override: child_logo();
if ( !function_exists( 'st_logo' ) ) {

function st_logo() {
	// Displays H1 or DIV based on whether we are on the home page or not (SEO)
	$heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
	if (of_get_option('use_logo_image')) {
		$class="graphic";
	} else {
		$class="text"; 		
	}
	// echo of_get_option('header_logo')
	$st_logo  = '<'.$heading_tag.' id="site-title" class="'.$class.'"><a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'">'.get_bloginfo('name').'</a></'.$heading_tag.'>'. "\n";
	$st_logo .= '<span class="site-desc '.$class.'">'.get_bloginfo('description').'</span>'. "\n";
	echo apply_filters ( 'child_logo' , $st_logo);
}
} // endif

add_action('st_header','st_logo', 3);



if ( !function_exists( 'logostyle' ) ) {

function logostyle() {
	if (of_get_option('use_logo_image')) {
	echo '<style type="text/css">
	#header #site-title.graphic a {background-image: url('.of_get_option('header_logo').');width: '.of_get_option('logo_width').'px;height: '.of_get_option('logo_height').'px;}</style>';
	}
}

} //endif

add_action('wp_head', 'logostyle');



if ( !function_exists( 'st_header_close' ) ) {

function st_header_close() {
	echo "</div></div><!--/#header-->";
}
} //endif

add_action('st_header','st_header_close', 4);



// Hook to add content after header

if ( !function_exists( 'st_below_header' ) ) {

function st_below_header() {
    do_action('st_below_header');
}

} //endif


// End Header Functions


// Navigation (menu)
if ( !function_exists( 'st_navbar' ) ) {

function st_navbar() {
	echo '<div id="navigation" class="row sixteen columns">';
	wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary'));
	echo '</div><!--/#navigation-->';
}

} //endif

// Before Content - st_before_content($columns);
// Child Theme Override: child_before_content();

if ( !function_exists( 'st_before_content' ) ) {

	function st_before_content($columns) {
	// 
	// Specify the number of columns in conditional statements
	// See http://codex.wordpress.org/Conditional_Tags for a full list
	//
	// If necessary, you can pass $columns as a variable in your template files:
	// st_before_content('six');
	//
	// Set the default
	
	if (empty($columns)) {
	$columns = 'eleven';
	} else {
	// Check the function for a returned variable
	$columns = $columns;
	}
	
	// Example of further conditionals:
	// (be sure to add the excess of 16 to st_before_sidebar as well)
	
	if (is_page_template('onecolumn-page.php')) {
	$columns = 'sixteen';
	}
	
	// check to see if bbpress is installed
	
	if ( class_exists( 'bbPress' ) ) {
	// force wide on bbPress pages
	if (is_bbpress()) {
	$columns = 'sixteen';
	}
	
	// unless it's the member profile
	if (bbp_is_user_home()) {
	$columns = 'eleven';
	} } // bbPress

	// Apply the markup
	echo "<a name=\"top\" id=\"top\"></a>";
	echo "<div id=\"content\" class=\"$columns columns\">";
	}
}


// After Content

if (! function_exists('st_after_content'))  {
    function st_after_content() {
    	echo "\t\t</div><!-- /.columns (#content) -->\n";
    }
}


// Before Sidebar - do_action('st_before_sidebar')

// call up the action
if ( !function_exists( 'before_sidebar' ) ) {
	
	function before_sidebar($columns) {
	// You can specify the number of columns in conditional statements
	// See http://codex.wordpress.org/Conditional_Tags for a full list
	//
	// If necessary, you can also pass $columns as a variable in your template files:
	// do_action('st_before_sidebar','six');
	//
	if (empty($columns)) {
	// Set the default
	$columns = 'five';
	} else {
	// Check the function for a returned variable
	$columns = $columns;
	}
	// Example of further conditionals:
	// (be sure to add the excess of 16 to st_before_content as well)
	// if (is_page() || is_single()) {
	// $columns = 'five';
	// } else {
	// $columns = 'four';
	// }
	// Apply the markup
	echo '<div id="sidebar" class="'.$columns.' columns" role="complementary">';
	}
} //endif
// create our hook
add_action( 'st_before_sidebar', 'before_sidebar');  



// After Sidebar
if ( !function_exists( 'after_sidebar' ) ) {
	function after_sidebar() {
	// Additional Content could be added here
	   echo '</div><!-- #sidebar -->';
	}
} //endif
add_action( 'st_after_sidebar', 'after_sidebar');  


// Before Footer

if (!function_exists('st_before_footer'))  {
    function st_before_footer() {
			$footerwidgets = is_active_sidebar('first-footer-widget-area') + is_active_sidebar('second-footer-widget-area') + is_active_sidebar('third-footer-widget-area') + is_active_sidebar('fourth-footer-widget-area');
			$class = ($footerwidgets == '0' ? 'noborder' : 'normal');
			echo '<div class="clear"></div><div id="footer" class="'.$class.' sixteen columns">';
    }
}

if ( !function_exists( 'st_footer' ) ) {

// The Footer
add_action('wp_footer', 'st_footer');
	do_action('st_footer');
	function st_footer() {
		//loads sidebar-footer.php
		get_sidebar( 'footer' );
		// prints site credits
		echo '<div id="credits">';
		echo of_get_option('footer_text');
		echo '<br /><a class="themeauthor" href="http://www.simplethemes.com" title="WordPress Themes">Theme by Simple Themes</a></div>';
}

}


// After Footer

if (!function_exists('st_after_footer'))  {
	
    function st_after_footer() {
			echo "</div><!--/#footer-->"."\n";
			echo "</div><!--/#wrap.container-->"."\n";
			// Google Analytics
			if (of_get_option('footer_scripts') <> "" ) {
				echo '<script type="text/javascript">'.stripslashes(of_get_option('footer_scripts')).'</script>';
			}
    }
}


if (!function_exists('st_formatter'))  {

// Editor Typography Improvements
function st_formatter($content) {
	$new_content = '';
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

	foreach ($pieces as $piece) {
		if (preg_match($pattern_contents, $piece, $matches)) {
			$new_content .= $matches[1];
		} else {
			$new_content .= wptexturize(wpautop($piece));
		}
	}

	return $new_content;
}
}

remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');
add_filter('the_content', 'st_formatter', 99);
add_filter('widget_text', 'st_formatter', 99);
add_filter('the_excerpt', 'st_formatter', 99);
add_filter('get_the_excerpt', 'st_formatter', 99);


// Enable Shortcodes in excerpts and widgets
add_filter('widget_text', 'do_shortcode');
add_filter( 'the_excerpt', 'do_shortcode');
add_filter('get_the_excerpt', 'do_shortcode');


// bbPress


if ( ! function_exists( 'bbp_skeleton_setup' ) ):

/**
 * Sets up theme support for bbPress
 *
 * If you're looking to add bbPress support into your own custom theme, you'll
 * want to make sure to use: add_theme_support( 'bbpress' );
 *
 * @since bbPress (r2652)
 */
function bbp_skeleton_setup() {
	// This theme comes bundled with bbPress template files
	add_theme_support( 'bbpress' );
}
/** Tell WordPress to run skeleton_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'bbp_skeleton_setup' );

endif;


if ( !function_exists( 'bbp_skeleton_enqueue_styles' ) ) :
/**
 * Load the theme CSS
 *
 * @since bbPress (r2652)
 *
 * @uses wp_enqueue_style() To enqueue the styles
 */
function bbp_skeleton_enqueue_styles () {

	$version = '20110807b';
		// bbPress specific styles
		wp_enqueue_style( 'bbp-skeleton-bbpress', get_template_directory_uri() . '/bbpress.css', 'skeleton', $version, 'screen' );
}
add_action( 'bbp_enqueue_scripts', 'bbp_skeleton_enqueue_styles' );

endif;



if ( !function_exists( 'bbp_skeleton_enqueue_scripts' ) ) :
/**
 * Enqueue the required Javascript files
 *
 * @since bbPress (r2652)
 *
 * @uses bbp_is_single_topic() To check if it's the topic page
 * @uses get_template_directory_uri() To get the stylesheet directory uri
 * @uses bbp_is_single_user_edit() To check if it's the profile edit page
 * @uses wp_enqueue_script() To enqueue the scripts
 */
function bbp_skeleton_enqueue_scripts () {

	$version = '20110807b';

	if ( bbp_is_single_topic() )
		wp_enqueue_script( 'bbp_topic', get_template_directory_uri() . '/js/topic.js', array( 'wp-lists' ), $version );

	if ( bbp_is_single_user_edit() )
		wp_enqueue_script( 'user-profile' );
}
add_action( 'bbp_enqueue_scripts', 'bbp_skeleton_enqueue_scripts' );
endif;

if ( !function_exists( 'bbp_skeleton_scripts' ) ) :
/**
 * Put some scripts in the header, like AJAX url for wp-lists
 *
 * @since bbPress (r2652)
 *
 * @uses bbp_is_single_topic() To check if it's the topic page
 * @uses admin_url() To get the admin url
 * @uses bbp_is_single_user_edit() To check if it's the profile edit page
 */
function bbp_skeleton_scripts () {
	if ( bbp_is_single_topic() ) : ?>

	<script type='text/javascript'>
		/* <![CDATA[ */
		var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
		/* ]]> */
	</script>

	<?php elseif ( bbp_is_single_user_edit() ) : ?>

	<script type="text/javascript" charset="utf-8">
		if ( window.location.hash == '#password' ) {
			document.getElementById('pass1').focus();
		}
	</script>

	<?php
	endif;
	}
add_filter( 'bbp_head', 'bbp_skeleton_scripts', -1 );
endif;

if ( !function_exists( 'bbp_skeleton_topic_script_localization' ) ) :

/**
 * Load localizations for topic script.
 *
 * These localizations require information that may not be loaded even by init.
 *
 * @since bbPress (r2652)
 *
 * @uses bbp_is_single_topic() To check if it's the topic page
 * @uses bbp_get_current_user_id() To get the current user id
 * @uses bbp_get_topic_id() To get the topic id
 * @uses bbp_get_favorites_permalink() To get the favorites permalink
 * @uses bbp_is_user_favorite() To check if the topic is in user's favorites
 * @uses bbp_is_subscriptions_active() To check if the subscriptions are active
 * @uses bbp_is_user_subscribed() To check if the user is subscribed to topic
 * @uses bbp_get_topic_permalink() To get the topic permalink
 * @uses wp_localize_script() To localize the script
*/

function bbp_skeleton_topic_script_localization () {
	if ( !bbp_is_single_topic() )
		return;

	$user_id = bbp_get_current_user_id();

	$localizations = array(
		'currentUserId' => $user_id,
		'topicId'       => bbp_get_topic_id(),
	);

	// Favorites
	if ( bbp_is_favorites_active() ) {
		$localizations['favoritesActive'] = 1;
		$localizations['favoritesLink']   = bbp_get_favorites_permalink( $user_id );
		$localizations['isFav']           = (int) bbp_is_user_favorite( $user_id );
		$localizations['favLinkYes']      = __( 'favorites',                                         'bbpress' );
		$localizations['favLinkNo']       = __( '?',                                                 'bbpress' );
		$localizations['favYes']          = __( 'This topic is one of your %favLinkYes% [%favDel%]', 'bbpress' );
		$localizations['favNo']           = __( '%favAdd% (%favLinkNo%)',                            'bbpress' );
		$localizations['favDel']          = __( '&times;',                                           'bbpress' );
		$localizations['favAdd']          = __( 'Add this topic to your favorites',                  'bbpress' );
	} else {
		$localizations['favoritesActive'] = 0;
	}

	// Subscriptions
	if ( bbp_is_subscriptions_active() ) {
		$localizations['subsActive']   = 1;
		$localizations['isSubscribed'] = (int) bbp_is_user_subscribed( $user_id );
		$localizations['subsSub']      = __( 'Subscribe',   'bbpress' );
		$localizations['subsUns']      = __( 'Unsubscribe', 'bbpress' );
		$localizations['subsLink']     = bbp_get_topic_permalink();
	} else {
		$localizations['subsActive'] = 0;
	}

	wp_localize_script( 'bbp_topic', 'bbpTopicJS', $localizations );
}
add_filter( 'bbp_enqueue_scripts', 'bbp_skeleton_topic_script_localization' );
endif;

if ( !function_exists( 'bbp_skeleton_dim_favorite' ) ) :
/**
 * Add or remove a topic from a user's favorites
 *
 * @since bbPress (r2652)
 *
 * @uses bbp_get_current_user_id() To get the current user id
 * @uses current_user_can() To check if the current user can edit the user
 * @uses bbp_get_topic() To get the topic
 * @uses check_ajax_referer() To verify the nonce & check the referer
 * @uses bbp_is_user_favorite() To check if the topic is user's favorite
 * @uses bbp_remove_user_favorite() To remove the topic from user's favorites
 * @uses bbp_add_user_favorite() To add the topic from user's favorites
 */
function bbp_skeleton_dim_favorite () {
	$user_id = bbp_get_current_user_id();
	$id      = intval( $_POST['id'] );

	if ( !current_user_can( 'edit_user', $user_id ) )
		die( '-1' );

	if ( !$topic = bbp_get_topic( $id ) )
		die( '0' );

	check_ajax_referer( "toggle-favorite_$topic->ID" );

	if ( bbp_is_user_favorite( $user_id, $topic->ID ) ) {
		if ( bbp_remove_user_favorite( $user_id, $topic->ID ) )
			die( '1' );
	} else {
		if ( bbp_add_user_favorite( $user_id, $topic->ID ) )
			die( '1' );
	}

	die( '0' );
}
add_action( 'wp_ajax_dim-favorite', 'bbp_skeleton_dim_favorite' );
endif;

if ( !function_exists( 'bbp_skeleton_dim_subscription' ) ) :
/**
 * Subscribe/Unsubscribe a user from a topic
 *
 * @since bbPress (r2668)
 *
 * @uses bbp_is_subscriptions_active() To check if the subscriptions are active
 * @uses bbp_get_current_user_id() To get the current user id
 * @uses current_user_can() To check if the current user can edit the user
 * @uses bbp_get_topic() To get the topic
 * @uses check_ajax_referer() To verify the nonce & check the referer
 * @uses bbp_is_user_subscribed() To check if the topic is in user's
 *                                 subscriptions
 * @uses bbp_remove_user_subscriptions() To remove the topic from user's
 *                                        subscriptions
 * @uses bbp_add_user_subscriptions() To add the topic from user's subscriptions
 */
function bbp_skeleton_dim_subscription () {
	if ( !bbp_is_subscriptions_active() )
		return;

	$user_id = bbp_get_current_user_id();
	$id      = intval( $_POST['id'] );

	if ( !current_user_can( 'edit_user', $user_id ) )
		die( '-1' );

	if ( !$topic = bbp_get_topic( $id ) )
		die( '0' );

	check_ajax_referer( "toggle-subscription_$topic->ID" );

	if ( bbp_is_user_subscribed( $user_id, $topic->ID ) ) {
		if ( bbp_remove_user_subscription( $user_id, $topic->ID ) )
			die( '1' );
	} else {
		if ( bbp_add_user_subscription( $user_id, $topic->ID ) )
			die( '1' );
	}

	die( '0' );
}
add_action( 'wp_ajax_dim-subscription', 'bbp_skeleton_dim_subscription' );
endif;