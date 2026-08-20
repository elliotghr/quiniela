<?php $quinielaRow = $quiniela->getRowArray(); ?>
<ul class="nav nav-pills nav-justified">
    <li class="nav-item">
        <a class="nav-link" href="#" data-qtabs="quiniela">Mi Quiniela</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-qtabs="globales">Resultados Globales</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-qtabs="porPartido">Resultados por Partido</a>
    </li>
    <?php if($quinielaRow['quiniela_usuario_id'] === getUserSession()): ?>
        <li class="nav-item">
            <a class="nav-link" href="#" data-qtabs="admin">Administración</a>
        </li>
    <?php endif; ?>
</ul>

<br />

<form id="formQuiniela" method="post" action ="#" data-qform="quiniela">
    <input type="hidden" name="quinielaId" value="<?=util_encode($quinielaRow['quiniela_id'])?>" />

    <select id="pronosticoId" name="pronosticoId" class="form-select modal-text" aria-label="Default select example">
        <?php foreach($pronosticos->getResultArray() as $pronostico): ?>
            <option value="<?=util_encode($pronostico['pronostico_id'])?>">Pronóstico #<?=$pronostico['consecutivo']?></option>
        <?php endforeach; ?>
    </select>

    <div id="divPartidos">
    </div>
</form>

<form id="formGlobales" method="post" action ="#" data-qform="globales">
    <input type="hidden" name="quinielaId" value="<?=util_encode($quinielaRow['quiniela_id'])?>" />

    <div id="divGlobales">
    </div>

    <div class="d-grid gap-2 justify-content-md-end d-md-flex fixed-bottom m-3">
        <button class="btn btn-secondary btnRegresar"><i class="fas fa-angle-left"></i> Regresar</button>
    </div>
</form>

<form id="formPartidos" method="post" action ="#" data-qform="porPartido">
    <input type="hidden" name="quinielaId" value="<?=util_encode($quinielaRow['quiniela_id'])?>" />

    <div id="divPorPartido">
    </div>

    <div class="d-grid gap-2 justify-content-md-end d-md-flex fixed-bottom m-3">
        <button class="btn btn-secondary btnRegresar"><i class="fas fa-angle-left"></i> Regresar</button>
    </div>
</form>

<form id="formAdmin" method="post" action ="#" data-qform="admin">
    <input type="hidden" name="quinielaId" value="<?=util_encode($quinielaRow['quiniela_id'])?>" />

    <div id="divAdmin">
    </div>

    <div class="d-grid gap-2 justify-content-md-end d-md-flex fixed-bottom m-3">
        <button class="btn btn-secondary btnRegresar"><i class="fas fa-angle-left"></i> Regresar</button>
    </div>
</form>