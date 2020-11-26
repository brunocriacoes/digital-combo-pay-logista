<?php

add_filter('woocommerce_thankyou_order_received_text', function ($str, $order) {
    global $wpdb;
    $id = $order->get_id();
    $type_payment = get_post_meta($id, 'pagamento_metodo', true);
    $barcode = get_post_meta($id, 'ORDER_BARCODE', true);
    $link = get_post_meta($id, 'ORDER_BOLETO', true);
    if( $type_payment == "boleto" ) :
        $str .= "
                    <li>
                        Seu código de barras é:  <b>{$barcode}</b>
                    </li>
                ";
        $str .= "
                    <li> 
                        Para imprimir seu boleto
                        <a href=\"{$link}\" target=\"_blank\">
                            Clique aqui
                        </a>
                    </li>
                ";
    endif;
    return $str;
}, 10, 2);
