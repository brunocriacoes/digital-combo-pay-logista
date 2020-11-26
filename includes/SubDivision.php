<?php

function get_division( $amount )
{
    $dcp = new DigitalComboPayGateway();
    $max_number_division = intval( $dcp->get_option("parcelar_em") );
    $min_amount_division = intval( $dcp->get_option("min_valor_por_parcela") );
    $divisions = [
        [
            "id" => 1,
            "text" => "1x de $amount",
        ]
    ];
    for( $i = 2; $i <= $max_number_division; $i++ ):
        $porcetage = $dcp->get_option("em_{$i}");
        $amount_plus_fee = $amount + ($amount / 100 * $porcetage);
        $division = $amount_plus_fee / $i;
        if(  $division > $min_amount_division ) :
            $division = number_format( $division, 2, ',', '.' );
            $amount_plus_fee = number_format( $amount_plus_fee, 2, ',', '.' );
            $divisions[] = [
                "id" => $i,
                "text" => "{$i}x de R$ {$division} TOTAL R$ {$amount_plus_fee}"
            ];
        endif;
    endfor;
    return $divisions;
}


add_action( 'woocommerce_before_add_to_cart_form', function() {
    global $product;
    $parcelas = get_division( $product->price );
    include __DIR__ . "/division-single.php";
} );


add_action( 'wp_enqueue_scripts', function() {
    
    wp_register_script( 'dcp-division-js', plugin_dir_url( __FILE__ ) . '/js/division.js', [], false, true);
    wp_register_style( 'dcp-division-css', plugin_dir_url( __FILE__ ) . '/css/division.css');
    
    wp_enqueue_style('dcp-division-css');
    wp_enqueue_script('dcp-division-js');

} );