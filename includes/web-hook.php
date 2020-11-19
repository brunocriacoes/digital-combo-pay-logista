<?php

add_action('woocommerce_api_digitalcombo', function () {
    header('HTTP/1.1 200 OK');
    global $wpdb;
    $table_perfixed = $wpdb->prefix . 'comments';
    $request = file_get_contents('php://input');
    $request = json_decode($request);
    set_log( "REQUEST WEB HOOK " . json_encode($request) );
    $request = [
        "type" => $request->type,
        "id"   => $request->payload->object->payment_method->id
    ];
    if (isset($request['id']) && isset($request['type'])) {
        $token   = $request['id'];
        $results = $wpdb->get_results("
                SELECT *
                FROM $table_perfixed
                WHERE  comment_content = 'TOKEN PEDIDO: $token'
            ");
        $pedido_id = $results[0]->comment_post_ID ?? 0;
        if ($pedido_id) {
            $order = new WC_Order($pedido_id);
        }
        switch ($request['type']) {
            case 'invoice.paid':
            case 'subscription.active':
            case 'transaction.succeeded':
                $order->update_status('completed', "Pagamento confirmado");
                break;
            case 'subscription.updated':
                break;
            case 'invoice.refunded':
            case 'invoice.expired':
            case 'invoice.overdue':
            case 'subscription.deleted':
            case 'subscription.expired':
            case 'subscription.suspended':
            case 'transaction.canceled':
            case 'transaction.failed':
            case 'transaction.reversed':
                $order->update_status('failed', "Pedido cancelado ou sumpenso");
                break;
        }
    }
    die;
});
