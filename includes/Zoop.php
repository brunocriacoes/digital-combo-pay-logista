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
    }
    public function post($path,  $params = [], $is_json = false)
    {
        $params = $is_json ? http_build_query($params) : json_encode($params);
        $defaults = [
            CURLOPT_POST           => true,
            CURLOPT_HEADER         => false,
            CURLOPT_URL            => self::URL_API . "/v1/marketplaces/" . $this->mkt_id . $path,
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
            ]
        );
        return [
            "status" => $request->status == 'active' ? true : false,
            "id" => $request->id
        ];
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
            ]
        );
        return [
            "status" => null,
            "id" => null
        ];
    }
    public function associatedCard()
    {
        $request = $this->post(
            "/cards",
            [
                "token" => $this->makerTokenCard()->id,
                "customer" => $this->makerBuyer()->id
            ]
        );
        return [
            "status" => null,
            "id" => null
        ];
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
                "reference_id" => $this->order_id,
                "split_rules" => $this->split_rules
            ]
        );
        return [
            "status" => null,
            "id" => null
        ];
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
                "constumerId" => $this->associatedCard()->id,
                "reference_id" => $this->order_id,
                "split_rules" => $this->split_rules,
                "logo" => $this->logo,
                "payment_method" => [
                    "expiration_date"   => $this->expiration_date,
                    "body_instructions" => $this->intructions
                ],
                "first_name"  => $this->first_name,
                "last_name"   => $this->last_name,
                "taxpayer_id" => $this->cpf_cnpj,
                "email"       => $this->email,
                "address"     => [
                    "line1"        => $this->line1,
                    "line2"        => $this->line2,
                    "neighborhood" => $this->bairro,
                    "city"         => $this->city,
                    "state"        => $this->state,
                    "postal_code"  => $this->postal_code,
                    "country_code" => "BR"
                ],
                "customerID" => $this->associatedCard()->id,
            ]
        );
        return [
            "status" => null,
            "id" => null
        ];
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
