<?php

/**
 * Plugin Name: Digital Combo Pay
 * Plugin URI: https://digitalcombo.com.br
 * Description: A forma mais fácil de vender através de boleto, cartão de crédito e débito recorrente via Woocommerce.
 * Version: 2.0.1
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Digital Combo Pay
 * Author URI: https://digitalcombo.com.br
 * Text Domain: digital-combo-pay-logista * 
 */
 
defined( 'ABSPATH' ) || exit;

$file_env =  __DIR__ . "/.env";
if( file_exists( $file_env ) ):
    define( 'DCP', parse_ini_file( $file_env ) );
endif;

add_action( 'plugins_loaded', function() {
    include_once __DIR__ . "/includes/DigitalComboPayGatway.php"; 
} );

include_once __DIR__ . "/includes/help.php"; 
include_once __DIR__ . "/includes/SubDivision.php"; 
include_once __DIR__ . "/includes/evendas.php"; 
include_once __DIR__ . "/includes/checkout-custon-fields.php"; 
include_once __DIR__ . "/includes/thank-your-custon.php"; 
include_once __DIR__ . "/includes/web-hook.php"; 
include_once __DIR__ . "/includes/email-custon.php"; 

add_filter( 'woocommerce_payment_gateways', function( $methods ) {
    $methods[] = 'DigitalComboPayGatway'; 
    return $methods;
} );

wp_enqueue_style( 'dcp-checkout-css', plugin_dir_url( __FILE__ ) . '/includes/css/checkout.css');
wp_enqueue_script( 'dcp-checkout-js', plugin_dir_url( __FILE__ ) . '/includes/js/checkout.js', [], false, true);