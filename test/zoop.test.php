<?php

/**
 * php .\test\zoop.test.php
 * 
 * https://dashboard.pagzoop.com/login
 * contato@digitalcombo.com.br
 * Seraph@121
 * 
 */

include __DIR__ . "/../includes/help.php";
include __DIR__ . "/../includes/Zoop.php";

$zoop = new Zoop( [
    "key_zpk" => "zpk_test_lcyUVmcv7ISdesnZe4m3w5eN",
    "mkt_id" => "83824523b30a4f44a6231c46319c8c12",
    "seller_id" => "6cf4bb1e78c6428786fc8fe6ddada3a6",
    "expiration_date" => 3,
    "intructions" => ["teste de instrução"],
    "logo" => "https://i.ibb.co/qnSvTQn/logo-digital-combo.png"
] );



$zoop->first_name = "Bruno";
$zoop->last_name = "Vieira";
$zoop->email = "Vieira";
$zoop->cpf_cnpj = "929.919.200-68";
$zoop->phone_number = "(82) 99977-6698";
$zoop->birthdate = "1987-09-18";
$zoop->address = "Rua Gonçalves dias";
$zoop->address_number = "42";
$zoop->address_complement = "casa";
$zoop->address_neighborhood = "JD Margaridas";
$zoop->address_city = "Taboão da serra";
$zoop->address_state = "SP";
$zoop->address_postal_code = "06786270";
$zoop->address_country_code = "BR";

$zoop->order_id = 1;
$zoop->amount = 300;

$zoop->holder_name = "Jose Luis da Silva";
$zoop->expiration_month = "09";
$zoop->expiration_year = "2030";
$zoop->card_number = "4539003370725497";
$zoop->security_code = "123";

$makerBuyer = $zoop->makerBuyer();
echo "Maker Buyer: ";
echo  ( $makerBuyer['status'] ? 'true' : 'false' ) . "\n";
echo " - BUYER: " . $makerBuyer["id"] . "\n";
if( !$makerBuyer['status'] ) :
    print_r( $zoop->makerBuyer() );
endif;

$makerTokenCard = $zoop->makerTokenCard();
echo "Maker Token Card: ";
echo  ( $makerTokenCard['status'] ? 'true' : 'false' ) . "\n";
echo " - TOKEN: " . $makerTokenCard["id"] . "\n";
if( !$makerTokenCard['status'] ) :
    print_r( $zoop->makerTokenCard() );
endif;

$associatedCard = $zoop->associatedCard();
echo "Associated Card:";
echo  ( $associatedCard['status'] ? 'true' : 'false' ) . "\n";
echo " - ID_CARD: " . $associatedCard["id"] . "\n";
if( !$associatedCard['status'] ) :
    print_r( $zoop->associatedCard() );
endif;

$zoop->type_pagamento = "boleto";
$pay =  $zoop->pay();
echo "Pay Boleto: ";
echo  ( $pay['status'] ? 'true' : 'false' ) . "\n";
echo  " - ID: " . $pay["id"] . "\n";
echo  " - BARCODE: " . $pay["barcode"] . "\n";
echo  " - URI: " . $pay["url"] . "\n";
if( !$pay['status'] ) :
    echo "\n";
    print_r( $zoop->pay() );
endif;

$zoop->type_pagamento = "card";
$pay =  $zoop->pay();
echo "Pay Card:";
echo  ( $pay['status'] ? 'true' : 'false' ) . "\n";
echo  " - ID: " . $pay["id"] . "\n";
if( !$pay['status'] ) :
    echo "\n";
    print_r( $zoop->pay() );
endif;
