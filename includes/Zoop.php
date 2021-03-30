<?php
class Zoop
{
    const URL_API = 'https://api.zoop.ws';
    const REQUEST_TIMEOUT = 12;
    private $key_zpk,
        $mkt_id,
        $expiration_date,
        $intructions,
        $logo,
        $seller_id;

    function __construct($params = [])
    {
        $this->key_zpk = $params["key_zpk"] . ":";
        $this->mkt_id = $params["mkt_id"];
        $this->seller_id = $params["seller_id"];
        $this->expiration_date = $params["expiration_date"];
        $this->intructions = $params["intructions"];
        $this->logo = $params["logo"];
        $this->split_rules = [];
        $this->customerId = null;
        $this->associated_card_id = null;
        $this->token_card_id = null;
    }
    public function post($path,  $params = [], $is_json = false)
    {
        $full_url = self::URL_API . "/v1/marketplaces/" . $this->mkt_id . $path;
        set_log( "POST $path -> ". json_encode( $params ) );
        $params = $is_json ? http_build_query($params) : json_encode($params);
        $defaults = [
            CURLOPT_POST           => true,
            CURLOPT_HEADER         => false,
            CURLOPT_URL            => $full_url,
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FORBID_REUSE   => true,
            CURLOPT_TIMEOUT        => self::REQUEST_TIMEOUT,
            CURLOPT_POSTFIELDS     => $params,
            CURLOPT_USERPWD        => $this->key_zpk,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => [
                "Content-Type" => "application/json; charset=UTF-8",
                "accept" => "application/json"
            ]
        ];
        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);
        set_log( "RES $path -> " . json_encode($result) );
        return $result;
    }
    function makerBuyer()
    {
        $request = $this->post(
            "/buyers",
            [
                "first_name" => $this->first_name,
                "last_name" => $this->last_name,
                "email" => $this->email,
                "phone_number" => $this->phone_number,
                "taxpayer_id" => $this->cpf_cnpj,
                "birthdate" => $this->birthdate,
                "address" => [
                    "line1" => $this->address,
                    "line2" => $this->address_number,
                    "line3" => $this->address_complement,
                    "neighborhood" => $this->address_neighborhood,
                    "city" => $this->address_city,
                    "state" => $this->address_state,
                    "postal_code" => $this->address_postal_code,
                    "country_code" => "BR"
                ]
            ], 
            true
        );
        $request = (array) $request;
        if( empty( $request["error"] ) ) :
            $this->customerId = $request["id"];
        endif;
        return array_merge( 
            $request,
            [
                "status" => empty( $request["error"] ) ? true : false,
                "id" => $request["id"]
            ]
        );
    }
    public function makerTokenCard()
    {
        $request = $this->post(
            "/cards/tokens",
            [
                "holder_name" => $this->holder_name,
                "expiration_month" => $this->expiration_month,
                "expiration_year" => $this->expiration_year,
                "card_number" => $this->card_number,
                "security_code" => $this->security_code
            ],
            true
        );
        $request = (array) $request;
        if( empty( $request["error"] ) ) :
            $this->token_card_id = $request["id"];
        endif;
        return array_merge(
            $request,
            [
                "status" => empty( $request["error"] ) ? true : false,
                "id" => empty( $request["error"] ) ? $request["id"] : null
            ]
        );
    }
    public function associatedCard()
    {
        $request = $this->post(
            "/cards",
            [
                "token" => $this->token_card_id,
                "customer" => $this->customerId
            ],
            true
        );
        $request = (array) $request;
        if( empty( $request["error"] ) ) :
            $this->associated_card_id = $request["id"];
        endif;
        return array_merge(
            $request,
            [
                "status" => empty( $request["error"] ) ? true : false,
                "id" => empty( $request["error"] ) ? $request["id"] : null
            ]
        );
    }
    public function cardPay()
    {
        $request = $this->post(
            "/transactions",
            [
                "amount" => $this->amount,
                "currency" => "BRL",
                "description" => "venda ecommerce",
                "payment_type" => "credit",
                "on_behalf_of" => $this->seller_id,
                "reference_id" => $this->associated_card_id,
                "customer" => $this->customerId,
                "split_rules" => $this->split_rules,
                "installment_plan" => [
                    "number_installments" => $this->parcela
                ]
            ],
            true
        );
        $request = (array) $request;
        if ( empty( $request["error"] ) ) :
            $this->transaction_id = $request["id"];
        endif;
        return array_merge(
            $request,
            [
                "status" => empty( $request["error"] ) ? true : false,
                "id" => empty( $request["error"] ) ? $request["id"] : null
            ]
        );
    }
    public function boletoPay()
    {
        $request = $this->post(
            "/transactions",
            [
                "amount" => $this->amount,
                "currency" => "BRL",
                "description" => "venda ecommerce",
                "payment_type" => "boleto",
                "on_behalf_of" => $this->seller_id,
                "customer" => $this->customerId,
                "reference_id" => $this->order_id,
                "split_rules" => $this->split_rules,
                "logo" => $this->logo,
                "payment_method" => [
                    "expiration_date"   => $this->expiration_date,
                    "body_instructions" => $this->intructions
                ]                
            ],
            true
        );
        $request = (array) $request;
        if ( empty( $request["error"] ) ) :
            $this->transaction_id = $request["id"];
        endif;
        return array_merge(
            $request,
            [
                "id" => empty( $request["error"] ) ? $request["payment_method"]->id : null,
                "status" => empty( $request["error"] ) ? true : false,
                "barcode" => empty( $request["error"] ) ? $request["payment_method"]->barcode : null ,
                "url" => empty( $request["error"] ) ? $request["payment_method"]->url : null
            ]
        );
    }
    function pay()
    {
        $method = "{$this->type_pagamento}Pay";
        if( method_exists( $this, $method ) ) :
            return $this->$method();
        else:
            return [
                "status" => false,
                "message_error" => "metodo nãp implementado"
            ];
        endif;
    }
}
