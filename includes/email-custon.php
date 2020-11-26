<?php

/**
 * http://loja.con/wp-admin/admin-ajax.php?action=previewemail&id_order=68
 */


add_action('wp_ajax_previewemail', function () {
    global $order;
    $orderId  = $_REQUEST['id_order'];
    $order    = new WC_Order($orderId);
    wc_get_template('emails/email-header.php', array('order' => $order));
    echo "<style>";
    wc_get_template('emails/email-styles.php', array('order' => $order));
    echo "</style>";
    wc_get_template('emails/email-order-details.php', array('order' => $order));
    wc_get_template('emails/email-footer.php', array('order' => $order));
});

add_action('woocommerce_email_before_order_table', function ($order) {
    $type_payment = get_post_meta($order->id, 'pagamento_metodo', true);
    $barcode = get_post_meta($order->id, 'ORDER_BARCODE', true);
    $link = get_post_meta($order->id, 'ORDER_BOLETO', true);

    if ($type_payment == "boleto") :
        echo "
            <center>
                <p>baixe agora seu boleto</p>
                <a 
                    href=\"{$link}\"
                    style=\"
                        border: 3px solid #666;
                        display: block;
                        padding: 20px;
                        text-decoration: none;
                        color: #666;
                    \"
                    target=\"_blank\"
                > 
                    BAIXAR BOLETO
                </a>			
                <br>
                <b>ou copie o codigo de barras</b>
                <span>{$barcode}</span>
                <br>
                <br>
            </center>
        ";
    endif;
});
