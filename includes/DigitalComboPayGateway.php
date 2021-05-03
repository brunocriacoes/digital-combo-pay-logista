<?php
class DigitalComboPayGateway  extends WC_Payment_Gateway
{
    function __construct()
    {
        $this->id = 'DigitalComboPayGateway';
        $this->icon = DCP['LOGO'];
        $this->method_title = DCP['NAME_PLUGIN'];
        $this->method_description  = DCP['DESCRIPTION_PLUGIN'];
        $this->has_fields = true;
        $this->init_form_fields();
        $this->init_settings();
        $this->order_button_text = $this->get_option('text_btn');
        $this->order_id = null;
        $this->payment_type = "boleto";
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        add_filter( 'woocommerce_order_button_text', function() {
            $text_button = $this->get_option('text_botao_finalizar');
            return !empty( $text_button ) ? $text_button : 'Finalizar';
        } );
    }
    public function get_split()
    {
        $split = $this->get_option("split");
        if ($split == "yes") :
            return [
                "recipient"             => $this->get_option("seller_id_to_split"),
                "percentage"            => (int) $this->get_option("percentual_split"),
                "amount"                => (int) $this->get_option("valor_split"),
                "charge_processing_fee" => (int) $this->get_option("liquido_split"),
                "liable"                => (int) $this->get_option("prezuiso_split"),
            ];
        else :
            return [];
        endif;
    }
    public function init_form_fields()
    {
        $this->form_fields =  require __DIR__ . '/config.php';
    }
    public function payment_fields()
    {
        $modo_de_pagamento = $this->get_option('meios_de_pagamento');
        $dev = $this->get_option("dev");
        include_once __DIR__ . "/checkout.php";
    }
    public function set_meta_barcode($code)
    {
        update_post_meta($this->order_id, 'ORDER_BARCODE', $code);
    }
    public function get_meta_barcode()
    {
        return get_post_meta($this->order_id, 'ORDER_BARCODE', true);
    }
    public function set_meta_boleto($link)
    {
        update_post_meta($this->order_id, 'ORDER_BOLETO', $link);
    }
    public function get_meta_boleto()
    {
        return get_post_meta($this->order_id, 'ORDER_BOLETO', true);
    }
    public function set_meta_ref($link)
    {
        update_post_meta($this->order_id, 'ORDER_REF', $link);
    }
    public function get_meta_ref()
    {
        return get_post_meta($this->order_id, 'ORDER_REF', true);
    }
    public function set_meta_type($type)
    {
        update_post_meta($this->order_id, 'pagamento_metodo', $type);
    }
    public function get_meta_type()
    {
        return get_post_meta($this->order_id, 'pagamento_metodo', true);
    }
    public function get_meta_customer_id()
    {
        $user_id =  get_current_user_id();
        return get_post_meta($user_id, "customerID_{$this->payment_type}", true);
    }
    public function set_meta_customer_id($token)
    {
        $user_id =  get_current_user_id();
        return update_post_meta($user_id, "customerID_{$this->payment_type}", $token);
    }
    public function apply_dicounts($amount, $porcetage, &$taxa)
    {

        $porcetage = $this->get_option("em_$porcetage");
        if (!empty($porcetage)) :
            $amount_plus_fee = $amount + ($amount / 100 * $porcetage);
            $fee =  $amount_plus_fee - $amount;
            $fee = number_format($fee, 2, '.', ',');
            $taxa = $fee;
            $amount = number_format($amount_plus_fee, 2, '.', ',');
        endif;
        return $amount;
    }
    function process_payment($order_id)
    {
        global $woocommerce;
        $order = new WC_Order($order_id);
        $this->order_id = $order_id;

        $work =  $this->get_option("dev") == "yes";
        $work = $work ? 'dev' : 'production';
        $seller_id = $work == "dev" ? "6cf4bb1e78c6428786fc8fe6ddada3a6" : $this->get_option("seller_id");
        $zoop  = new Zoop([
            "key_zpk" => DCP[$work]["KEY_ZPK"],
            "mkt_id" => DCP[$work]["MKT_ID"],
            "seller_id" => $seller_id,
            "expiration_date" => 3,
            "intructions" => ["teste de instrução"],
            "logo" => "https://i.ibb.co/qnSvTQn/logo-digital-combo.png"
        ]);

        $mes_ano_card = isset($_POST["card_valid"]) ? $_POST["card_valid"] : "00/00";
        $mes_ano_card_boom = str_replace('/', '', $mes_ano_card );
        // $mes_ano_card_boom = explode('/', $mes_ano_card);
        $zoop->mes_ano_card = $mes_ano_card;
        $zoop->expiration_month = substr( $mes_ano_card_boom, 0, 2 );
        $zoop->expiration_year = str_pad(substr( $mes_ano_card_boom, 2, 4 ) , 4 , '20' , STR_PAD_LEFT) ;
        $zoop->type_pagamento = isset($_POST["type_pagamento"]) ? $_POST["type_pagamento"] : 'boleto';
        $zoop->card_number = isset($_POST["card_number"]) ? str_replace(' ', '', $_POST["card_number"])  : '';
        $zoop->security_code = isset($_POST["card_cvv"]) ? $_POST["card_cvv"] : '';
        $zoop->holder_name = isset($_POST["card_name"]) ? $_POST["card_name"] : '';
        $zoop->parcela = isset($_POST["number_installments"]) ? $_POST["number_installments"] : '1';

        $total_cart = $order->get_total();
        $taxa = 0;
        $total_cart = $this->apply_dicounts($total_cart, $zoop->parcela, $taxa);
        $zoop->amount = str_replace('.', '', "$total_cart");

        $country_code = $order->get_shipping_country();
        $calculate_tax_for = array(
            'country' => $country_code,
            'state' => '',
            'postcode' => '',
            'city' => ''
        );
        $item_fee = new WC_Order_Item_Fee();
        $item_fee->set_name("Taxa por Parcelamento");
        $item_fee->set_amount($taxa);
        $item_fee->set_tax_class('');
        $item_fee->set_tax_status('Taxa por Parcelamento');
        $item_fee->set_total($taxa);
        $item_fee->calculate_taxes($calculate_tax_for);
        $order->add_item($item_fee);

        $zoop->number_installments = isset($_POST["number_installments"]) ? $_POST["number_installments"] : 1;
        $zoop->cpf_cnpj = $order->get_meta('_billing_cpf');
        $zoop->first_name = $order->get_billing_first_name();
        $zoop->last_name = $order->get_billing_last_name();
        $zoop->email = $order->get_billing_email();
        $zoop->address = $order->get_billing_address_1();
        $zoop->address_number = $order->get_billing_address_2();
        $zoop->address_city = $order->get_billing_city();
        $zoop->address_state = $order->get_billing_state();
        $zoop->address_postal_code = $order->get_billing_postcode();
        $zoop->address_country_code = "BR";
        $zoop->address_neighborhood = $order->get_meta('_billing_neighborhood');
        $zoop->split_rules = $this->get_split();
        $zoop->order_id = $order_id;
        $zoop->user_id = get_current_user_id();
        $zoop->phone_number = $order->get_billing_phone();
        $zoop->birthdate = "";
        $zoop->address_complement = "";
        $res = $zoop->makerBuyer();
        $this->set_meta_type($zoop->type_pagamento);
        if ($zoop->type_pagamento == "card") :
            $zoop->makerTokenCard();
            $zoop->associatedCard();
        endif;
        $pay = $zoop->pay();
        $this->set_meta_ref($pay["payment_method"]->id);
        if ($zoop->type_pagamento == "boleto") :
            $this->set_meta_barcode($pay["barcode"]);
            $this->set_meta_boleto($pay["url"]);
        endif;
        if ($pay["status"]) :
            $order->calculate_totals();
            $order->update_status('on-hold', __('Awaiting cheque payment', 'woocommerce'));
            $woocommerce->cart->empty_cart();
        endif;
        $redirect = !empty($this->get_option("custon_slug_thank_you")) ? $this->get_option("custon_slug_thank_you") : $this->get_return_url($order);

        return array(
            'result' => $pay["status"] ? "success" : "error",
            'redirect' => $redirect
        );
    }
}
