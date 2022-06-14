<?php

add_filter( 'user_contactmethods', 'modify_user_contact_methods' );

function modify_user_contact_methods( $methods ) {
    // Add user info
    $methods['user_columna']   = __( 'Columna del Autor'   );
   

    return $methods;
}


function custom_single_template($the_template) {
    foreach ( (array) get_the_category() as $cat ) {
        if ( locate_template("single-{$cat->slug}.php") ) {
            return locate_template("single-{$cat->slug}.php");
        }
    }
    return $the_template;
}
add_filter( 'single_template', 'custom_single_template');


// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'font-awesome','simple-line-icons','magnific-popup','slick','oceanwp-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// ADD CUSTOM CSS/JS 

    function wp_theme_script_includer() {
		

     wp_register_script('custom', get_theme_file_uri('js/custom.js'), array('jquery'), '1', true );
     wp_enqueue_script('custom');
		
    
    }
    add_action( 'wp_enqueue_scripts', 'wp_theme_script_includer'); 

// END ENQUEUE PARENT ACTION
