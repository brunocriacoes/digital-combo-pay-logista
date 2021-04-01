<?php
$option_play = require __DIR__ . "/options-pay.php";
global $woocommerce;
$parcelas = get_division( WC()->cart->total );
?>
<div class="card_form">
    <div>
        <div class="escolha_tipo">
            <?php foreach ($option_play as $meio_pagamento) : ?>
                <input type="radio" oninput="globalThis.opcao_pagamento( '<?= $meio_pagamento['value'] ?>' )" name="type_pagamento" value="<?= $meio_pagamento['value'] ?>" id="<?= $meio_pagamento['id'] ?>" hidden>
                <label for="<?= $meio_pagamento['id'] ?>" <?= is_option_valid( $modo_de_pagamento, $meio_pagamento['value'] ) ?> >
                    <img src="<?= $meio_pagamento['ico'] ?>" alt="card">
                    <small><?= $meio_pagamento['text'] ?></small>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div id="card_digital_combo" hidden >
    <div class="card" style="background-image: url( '<?= DCP['BG_CARD'] ?>' );">
        <div class="card__branch">
            <img src="<?= DCP['CHIP_CARD'] ?>" alt="">
            <span></span>
            <img src="<?= DCP['ICO'] ?>" alt="">
        </div>
        <div class="card_number" id="vNumber">0000 0000 0000 0000</div>
        <div class="card__valid_cvv">
            <div>
                <span>VALID</span>
                <b id="vValid">02/2020</b>
            </div>
            <div>
                <span>CVV</span>
                <b id="vCvv">123</b>
            </div>
        </div>
        <div class="card_name" id="vName">DIGITA AQUI SEU NOME</div>
    </div>
    <div class="card_form">
        <div>
            <label for="">Número<b>*</b></label>
            <input type="text" -value="4539 0033 7072 5497" name="card_number" id="iNumber" oninput="globalThis.card_number()" placeholder="0000 0000 0000 0000" require>
        </div>
        <div>
            <label for="">Nome<b>*</b></label>
            <input type="text" -value="Bruno AP R VIEIRA" name="card_name" id="iName" placeholder="DIGITA AQUI SEU NOME" oninput="globalThis.card_name()" require>
        </div>
        <div class="card_grid_cvv_valid">
            <div>
                <label for="">Validade<b>*</b></label>
                <input type="text" name="card_valid" -value="12/30" id="iValid" placeholder="MM/AAAA" oninput="globalThis.card_valid()" require>
            </div>
            <div>
                <label for="">CVV<b>*</b></label>
                <input type="text" -value="123" name="card_cvv" id="iCvv" placeholder="123" oninput="globalThis.card_cvv()" require>
            </div>
        </div>
        <div>
            <label for="">Parcelar em </label>
            <select name="number_installments">
                <?php foreach( $parcelas as $parcela ) : ?>
                    <option value="<?= $parcela["id"] ?>"><?= $parcela["text"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>
<?php if( $dev == 'yes') : ?>
    <div id="is_dev"> O Site esta em mode De desenvolvimento todas as compras são invalidas </div>
<?php endif; ?>
<script>
    globalThis.card_number = () => {
        let $iNumber = document.querySelector("#iNumber")
        let $vNumber = document.querySelector("#vNumber")
        let mascara = $iNumber.value
        mascara = mascara.replace(/\D/gi, '')
        mascara = mascara.replace(/(\d{4})(.*)/gi, '$1 $2')
        mascara = mascara.replace(/(\d{4}\s)(\d{4})(.*)/gi, '$1$2 $3')
        mascara = mascara.replace(/(\d{4}\s)(\d{4}\s)(\d{4})(.*)/gi, '$1$2$3 $4')
        mascara = mascara.replace(/(\d{4}\s)(\d{4}\s)(\d{4}\s)(\d{4})(.*)/gi, '$1$2$3$4')
        if (mascara.length > 0) {
            $vNumber.innerHTML = mascara
            $iNumber.value = mascara
        } else {
            $vNumber.innerHTML = "0000 0000 0000 0000"
            $iNumber.value = mascara
        }
    }
    globalThis.card_name = () => {
        let $iName = document.querySelector("#iName")
        let $vName = document.querySelector("#vName")
        if ($iName.value.length > 0) {
            $vName.innerHTML = $iName.value
        } else {
            $vName.innerHTML = "Fulano da Silva"
        }
    }
    globalThis.card_valid = () => {
        let $iValid = document.querySelector("#iValid")
        let $vValid = document.querySelector("#vValid")
        let mascara = $iValid.value
        mascara = mascara.replace(/\D/gi, '')
        mascara = mascara.replace(/(\d{2})(\d{4})(.*)/gi, '$1/$2')
        if (mascara.length > 0) {
            $vValid.innerHTML = mascara
            $iValid.value = mascara
        } else {
            $vValid.innerHTML = "02/2020"
        }
    }
    globalThis.card_cvv = () => {
        let $iCvv = document.querySelector("#iCvv")
        let $vCvv = document.querySelector("#vCvv")
        let mascara = $iCvv.value
        mascara = mascara.replace(/\D/gi, '')
        mascara = mascara.replace(/(\d{3})(.*)/gi, '$1')
        if (mascara.length > 0) {
            $vCvv.innerHTML = mascara
            $iCvv.value = mascara
        } else {
            $vCvv.innerHTML = "123"
        }
    }
    globalThis.opcao_pagamento = tipo => {        
        let $card_digital_combo = document.querySelector("#card_digital_combo")
        if (tipo == "card" ) {
            $card_digital_combo.removeAttribute('hidden')
        } else {
            $card_digital_combo.setAttribute('hidden', '')
        }
    }
</script>