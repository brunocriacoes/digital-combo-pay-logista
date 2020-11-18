
<p>ou em <b><?= $max_number_division ?></b> de <b>R$ <?= $division ?> </b> </p>
<div class="grid-division" id="dcp-division">
    <span class="btn-more-division" id="dcp_show_division"> Ver parcelas <small>&#x21D3;</small>  </span>
    <?php foreach( $parcelas as $parcela ) : ?>
        <span> <b><?= $parcela['vezes'] ?>x</b> de <b>R$ <?= $parcela['sub_total'] ?></b> Total <b>R$ <?= $parcela['total'] ?></b> </span>
    <?php endforeach; ?>
</div>