<?php

add_filter('woocommerce_thankyou_order_received_text', function ($str, $order) {
    global $wpdb;
    $table_perfixed = $wpdb->prefix . 'comments';
    $id             = $order->get_id();
    $results = $wpdb->get_results("
                    SELECT *
                    FROM $table_perfixed
                    WHERE comment_post_ID = '$id'
                
                ");
    $codigo_de_barra = array_filter($results, function ($comment) {
        return stripos($comment->comment_content, "CODIGO DE BARRAS:") !== false;
    });
    $codigo_de_barra = array_values($codigo_de_barra);
    if (count($codigo_de_barra) > 0) {
        $codigo_de_barra = $codigo_de_barra[0]->comment_content;
        $codigo_de_barra = str_replace("CODIGO DE BARRAS:", '', $codigo_de_barra);
        $str .= "
                        <li>Seu código de barras é:  <b>$codigo_de_barra</b></li>
                    ";
    }
    $link_boleto = array_filter($results, function ($comment) {
        return stripos($comment->comment_content, "URL BOLETO:") !== false;
    });
    $link_boleto = array_values($link_boleto);
    if (count($link_boleto) > 0) {
        $link_boleto = $link_boleto[0]->comment_content;
        $link_boleto = str_replace("URL BOLETO:", '', $link_boleto);
        $str .= "
                        <li> 
                            Para imprimir seu boleto
                            <a href=\"$link_boleto\" target=\"_blank\">
                                Clique aqui
                            </a>
                        </li>
                    ";
    }
    return $str;
}, 10, 2);
