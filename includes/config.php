<?php

return [
    "enabled" => [
        "title"   => "Ativar/Desativar",
        "type"    => "checkbox",
        "label"   => "Digital Combo Pay",
        "default" => "yes"
    ],
    "dev" => [
        "title"   => "Modo de teste",
        "type"    => "checkbox",
        "label"   => "Ativar modo de Teste",
        "default" => "yes"
    ],
    "seller_id" => [
        "title"       => "ID DE VENDEDOR",
        "type"        => "text",
        "default"     => null,
    ],
    "text_botao_finalizar" => [
        "title"       => "Texto botão finalizar",
        "type"        => "text",
        "default"     => "Finalizar",
    ],
    "custon_slug_thank_you" => [
        "title"       => "Slug página de obrigado",
        "type"        => "text",
        "default"     => null,
    ],
    "meios_de_pagamento" => [
        "title"   => "Boleto ou Cartão",
        "type"    => "select",
        "default" => "cartao_credito_e_boleto",
        "options" => [
            "cartao_credito_e_boleto" => "Cartão Crédito e Boleto",
            "cartao_de_credito" => "Somente via Cartão Crédito",
            "boleto" => "Somente via Boleto",
        ]
    ],
    "dias_para_vencer_boleto" => [
        "title"       => "Vencimento Boleto",
        "type"        => "number",
        "default"     => 0,
    ],
    "dias_carencia" => [
        "title"       => "Dias de Carência",
        "type"        => "number",
        "default"     => 0,
    ],
    "periodo_tolerancia" => [
        "title"       => "Período de Tolerância",
        "type"        => "number",
        "default"     => 0,
    ],
    "split" => [
        "title"   => "Divisão",
        "label"   => "Ativar a divisão de pagamento",
        "type"    => "checkbox",
        "default" => "no",

    ],
    "prezuiso_split" => [
        "title"   => "Arcar/Prejuízo",
        "label"   => "Recebedor arcar com prejuiso caso extorno",
        "type"    => "checkbox",
        "default" => "no",
    ],
    "liquido_split" => [
        "title"   => "Líquido/Bruto",
        "label"   => "Dividido pelo seu total líquido",
        "type"    => "checkbox",
        "default" => "no",
    ],
    "percentual_split" => [
        "title"       => "Percentual da divisão",
        "type"        => "number",
        "default"     => 0,
    ],
    "valor_split" => [
        "title"       => "Divisão valor fixo",
        "type"        => "number",
        "default"     => 0,
    ],
    "seller_id_to_split" => [
        "title"       => "ID de vendodor a Dividir",
        "type"        => "text",
        "default"     => null,
    ],
    "parcelar_em" => [
        "title"       => "Parcelar em no máximo",
        "type"        => "select",
        "default"     => "1",
        "options" => [
            1 => "Somente pagamento a vista",
            2 => "em até 2 vezes",
            3 => "em até 3 vezes",
            4 => "em até 4 vezes",
            5 => "em até 5 vezes",
            6 => "em até 6 vezes",
            7 => "em até 7 vezes",
            8 => "em até 8 vezes",
            9 => "em até 9 vezes",
            10 => "em até 10 vezes",
            11 => "em até 11 vezes",
            12 => "em até 12 vezes"
        ]
    ],
    "min_valor_por_parcela" => [
        "title"       => "Mínimo valor por parcela",
        "type"        => "number",
        "default"     => 10.00,
    ],
    "em_2" => [
        "title"       => "% por pagar em até 2x",
        "type"        => "number",
        "default"     => 0,
    ],
    "em_3" => [
        "title"       => "% por pagar em até 3x",
        "type"        => "number",
        "default"     => 0,
    ],
    "em_4" => [
        "title"       => "% por pagar em até 4x",
        "type"        => "number",
        "default"     => 0,
    ],
    "em_5" => [
        "title"       => "% por pagar em até 5x",
        "type"        => "number",
        "default"     => 0,
    ],
    "em_6" => [
        "title"       => "% por pagar em até 6x",
        "type"        => "number",
        "default"     => 0,
    ],
    "em_7" => [
        "title"       => "% por pagar em até 7x",
        "type"        => "number",
        "default"     => 0,
    ],
    "em_8" => [
        "title"       => "% por pagar em até 8x",
        "type"        => "number",
        "default"     => 0,
    ],
    "em_9" => [
        "title"       => "% por pagar em até 9x",
        "type"        => "number",
        "default"     => 0,
    ],
    "em_10" => [
        "title"       => "% por pagar em até 10x",
        "type"        => "number",
        "default"     => 0,
    ],
    "em_11" => [
        "title"       => "% por pagar em até 11x",
        "type"        => "number",
        "default"     => 0,
    ],    
    "em_12" => [
        "title"       => "% por pagar em até 12x",
        "type"        => "number",
        "default"     => 0,
    ]    
];