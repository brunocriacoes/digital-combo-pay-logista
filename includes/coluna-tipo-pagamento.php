
<?php

add_filter('manage_edit-shop_order_columns', function ($cols) {
	$cols["type_payment"] = "Tipo Pagamento";
	return $cols;
});

function type_payment( $pay ) {
    $playload = [
        "card" => "Cartão",
        "boleto" => "Boleto",
        "undefined" => "Não Definido",
    ];
    return $playload[$pay] ?? 'não definido';
}

add_action('manage_shop_order_posts_custom_column', function ($col_name) {
	global $post;
    $ID = $post->ID;
    $tipo_pagamento = get_post_meta( $ID, 'pagamento_metodo', true);
    $tipo_pagamento = strlen($tipo_pagamento) > 3 ? $tipo_pagamento : 'undefined';
    $tipo_pagamento = type_payment($tipo_pagamento);
    $link = get_post_meta( $ID, 'ORDER_BOLETO', true);
	if ('type_payment' == $col_name) :
        if( $tipo_pagamento == 'Boleto'):
            echo "{$tipo_pagamento} <a href=\"{$link}\" target=\"_blank\"> LINK </a>";
        else:
            echo " {$tipo_pagamento}";
        endif;
	endif;
});



