<select id="partidoId" name="partidoId" class="form-select modal-text" aria-label="Default select example">
    <?php foreach($fixtures as $fixture): ?>
        <option value="<?=util_encode((string)$fixture['id'])?>" <?= $fixture['date'] <= date('c') ? 'selected' : '' ?>><?=date_format(new DateTime($fixture["date"]), "d-M-Y H:i");?> / <?=$fixture['home_name']?> - <?=$fixture['away_name']?></option>
    <?php endforeach; ?>
</select>

<div id="divResultadosPorPartido">

</div>