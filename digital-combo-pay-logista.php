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
    define( 'DCP', parse_ini_file( $file_env, TRUE, INI_SCANNER_RAW ) );
endif;

include_once __DIR__ . "/includes/help.php"; 
include_once __DIR__ . "/includes/SubDivision.php"; 
include_once __DIR__ . "/includes/evendas.php"; 
include_once __DIR__ . "/includes/checkout-custon-fields.php"; 
include_once __DIR__ . "/includes/thank-your-custon.php"; 
include_once __DIR__ . "/includes/web-hook.php"; 
include_once __DIR__ . "/includes/email-custon.php"; 
include_once __DIR__ . "/includes/Zoop.php"; 
include_once __DIR__ . "/includes/coluna-tipo-pagamento.php"; 

add_action( 'plugins_loaded', function() {
    include_once __DIR__ . "/includes/DigitalComboPayGateway.php"; 
} );

add_filter( 'woocommerce_payment_gateways', function( $methods ) {
    $methods[] = 'DigitalComboPayGateway'; 
    return $methods;
} );

add_action( 'wp_enqueue_scripts', function() {
    wp_register_style( 'dcp-checkout-css', plugin_dir_url( __FILE__ ) . '/includes/css/checkout.css');
    wp_enqueue_style('dcp-checkout-css');
} );