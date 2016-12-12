<?php
/**
 * Generate child theme functions and definitions
 *
 * @package Generate
 */
 
 if ( ! function_exists( 'generate_header_items' ) ) :
/**
 * Build the header
 *
 * Wrapping this into a function allows us to customize the order
 *
 * @since 1.2.9.7
 */
function generate_header_items() 
{
	// Header widget
	generate_construct_header_widget();
	
	// Site logo
	generate_construct_logo();
	
	// Site title and tagline
	generate_construct_site_title();
	
}
endif;

if ( ! function_exists( 'generate_construct_logo' ) ) :
/**
 * Build the logo
 *
 * @since 1.3.28
 */
function generate_construct_logo()
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	// Get our logo URL if we're using the custom logo
	$logo_url = ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' ) : false;
	
	// Get our logo from the custom logo or our GP setting
	$logo = ( $logo_url ) ? $logo_url[0] : $generate_settings['logo'];
	
	// If we don't have a logo, bail
	if ( empty( $logo ) )
		return;
	
	do_action( 'generate_before_logo' );
	
	// Print our HTML
	printf( 
		'<div class="site-logo">
			<a href="%1$s" title="%2$s" rel="home">
				<img class="header-image" src="%3$s" alt="%2$s" title="%2$s" />
			</a>
		</div>',
		apply_filters( 'generate_logo_href' , esc_url( home_url( '/' ) ) ),
		apply_filters( 'generate_logo_title', esc_attr( get_bloginfo( 'name', 'display' ) ) ),
		apply_filters( 'generate_logo', esc_url( $logo ) )
	);
	
	do_action( 'generate_after_logo' );
}
endif;

if ( ! function_exists( 'generate_construct_site_title' ) ) :
/**
 * Build the site title and tagline
 *
 * @since 1.3.28
 */
function generate_construct_site_title()
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	// Get the title and tagline
	$title = get_bloginfo( 'title' );
	$tagline = get_bloginfo( 'description' );
	
	// If the disable title checkbox is checked, or the title field is empty, return true
	$disable_title = ( '1' == $generate_settings[ 'hide_title' ] || '' == $title ) ? true : false; 
	
	// If the disable tagline checkbox is checked, or the tagline field is empty, return true
	$disable_tagline = ( '1' == $generate_settings[ 'hide_tagline' ] || '' == $tagline ) ? true : false;
	
	// Site title and tagline
	if ( false == $disable_title || false == $disable_tagline ) : ?>
		<div class="site-branding">
			<?php if ( false == $disable_title ) : ?>
				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="main-title" itemprop="headline"><a href="<?php echo apply_filters( 'generate_site_title_href', esc_url( home_url( '/' ) ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="main-title" itemprop="headline"><a href="<?php echo apply_filters( 'generate_site_title_href', esc_url( home_url( '/' ) ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php endif; ?>
			<?php endif;
				
			if ( false == $disable_tagline ) : ?>
				<p class="site-description"><?php echo html_entity_decode( get_bloginfo( 'description', 'display' ) ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif;
}
endif;
