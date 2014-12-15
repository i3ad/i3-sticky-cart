<?php
/*
Plugin Name: i3 Sticky Cart
Plugin URI: 
Description: 
Author: Mo
Version: 0.1
Author URI: 
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    /**
     * Inserts and displays the template in wp_footer
     **/
    function wccs_template_function() {
        	global $woocommerce;
          
        	echo    '<div id="wccs-container">';
        	echo 		'<span class="wccs-handle">';
                            wccs_button_template(); // Get the button template from the function
            echo 		'</span>';
        	echo 		'<div id="wccs-content" class="cf">';
                            // Check for WooCommerce 2.0 and display the cart widget
                            if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
                                the_widget( 'WC_Widget_Cart', 'title=' ); // Title is also hidden via css
                            } else {
                                the_widget( 'WooCommerce_Widget_Cart', 'title=' ); // Title is also hidden via css
                            } // END WooCommerce 2.0 check
        	echo  		'</div>';
        	echo 	'</div><!-- /END #wccs-container -->';
    }
    add_action('wp_footer', 'wccs_template_function', 100);

    /**
     * The button template, called in the template and ajax function, this is also the slide-handle
     **/
    function wccs_button_template() {
        global $woocommerce; ?>

	        <a class="wccs-button" href="#">
	            <span class="label"><?php _e('Cart', 'woocommerce'); ?></span>
	            <?php
	                echo '<span class="count">(' . sprintf( _n( '%d item', '%d items', intval( $woocommerce->cart->get_cart_contents_count() ), 'woocommerce' ), intval( $woocommerce->cart->get_cart_contents_count() ) ) . ')</span> ';
	                echo wp_kses_post( $woocommerce->cart->get_cart_total() );
	            ?>
	        </a>
    <?php }

    /**
     * Update the button via ajax, when products are added to cart
     **/
    function wccs_button_fragments( $fragments ) {
        global $woocommerce;

        ob_start();
        wccs_button_template(); // Get the button template from the function
        $fragments['.wccs-button'] = ob_get_clean();

        return $fragments;
    }
    add_filter('add_to_cart_fragments', 'wccs_button_fragments');

    /**
     * Enqueue the stylesheet, js (in wp_footer) and also fontawesome
     **/
    function wccs_styles_scripts() {
    	if ( ! is_admin() ) {
        	wp_enqueue_style( 'wccs-styles', plugins_url('/assets/css/style.css', __FILE__) );
        	wp_enqueue_script( 'wccs-scripts', plugins_url('/assets/js/functions.js', __FILE__), array('jquery'), '', true);
        	
        	wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', null, '4.0.1' );
        }  
    }
    add_action('wp_enqueue_scripts', 'wccs_styles_scripts');

}// END WooCommerce is active check