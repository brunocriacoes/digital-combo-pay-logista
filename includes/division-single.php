

<div class="grid-division" id="dcp-division">
    <span class="btn-more-division" id="dcp_show_division"> Ver parcelas <small>&#x21D3;</small>  </span>
    <?php foreach( $parcelas as $parcela ) : ?>
        <span> <?= $parcela["text"] ?> </span>
    <?php endforeach; ?>
</div>