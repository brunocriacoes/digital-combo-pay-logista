<?php

add_action( 'woocommerce_before_add_to_cart_form', function() {
    global $product;
    $dcp = new DigitalComboPayGateway();
    $price = $product->price;
    $max_number_division = $dcp->get_option("parcelar_em");
    $min_amout_division = $dcp->get_option("min_valor_por_parcela");
    $division = number_format( ( $price / $max_number_division ), 2, ',', '.' );
    for( $em = 1; $em <= $max_number_division; $em++ ) :
        $pctm = (int) $dcp->get_option("em_$em");
        $juros =  number_format( ( $price - $price * $pctm / 100.0), 2, ',', '.' );
        $parcelas[] = [
            "vezes" => $em,
            "sub_total" => number_format( ( $price / $em ), 2, ',', '.' ),
            "total" => $juros
        ];
    endfor;
    include __DIR__ . "/division-single.php";
} );

wp_enqueue_script( 'dcp-division-js', plugin_dir_url( __FILE__ ) . '/js/division.js', [], false, true);
wp_enqueue_style( 'dcp-division-css', plugin_dir_url( __FILE__ ) . '/css/division.css');

