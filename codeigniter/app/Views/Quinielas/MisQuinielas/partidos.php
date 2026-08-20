<?php $adminRow = $admin->getRowArray(); ?>
<?php $pronosticoRow = $pronostico->getRowArray() ?>
<?php $quinielaRow = $quiniela->getRowArray(); ?>
<?php $inTime = date_format(new DateTime($quinielaRow['fecha_inicio']), "c") >= date_format(new DateTime(), "c") ? true : false; ?>

<?php if(!$inTime): ?>
    <?php
        $fraction = env("quiniela.refresh");
        $minutes = (int)date('i');
        $hour = (int)date('H');

        $mod = $minutes % $fraction;

        $prevMin = $minutes - $mod;
        $prevMin = ($prevMin < 10) ? '0' . $prevMin : $prevMin;

        $nextMin = $prevMin + $fraction;
        $nextMin = ($nextMin == 60) ? 0 : $nextMin;
        $nextMin = ($nextMin < 10) ? '0' . $nextMin : $nextMin;

        $nextHour = (($prevMin + $fraction) == 60) ? $hour + 1 : $hour;
        $nextHour = ($nextHour == 24) ? 0 : $nextHour;
        $nextHour = ($nextHour < 10) ? '0' . $nextHour : $nextHour;

        $prevHour = $hour;
        $prevHour = ($prevHour < 10) ? '0' . $prevHour : $prevHour;

        $minutes = ($minutes < 10) ? '0' . $minutes : $minutes;
        $hour = ($hour < 10) ? '0' . $hour : $hour;
    ?>

    <div class="alert alert-info mt-3 mb-3" role="alert">
        <div class="row">
            <div class="col-6 text-center">
                <i class="far fa-clock"></i> Última actualización: <?=$prevHour . ':' . $prevMin?>
            </div>
            <div class="col-6 text-center">
                <i class="fas fa-history"></i> Siguiente actualización: <?=$nextHour . ':' . $nextMin?>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- <?php if($inTime): ?>
    <div class="alert alert-info mt-3 mb-3" role="alert">
        <i class="far fa-calendar-alt"></i> Fecha máxima para la captura del pronóstico: <b><?=date_format(new DateTime($quinielaRow['fecha_inicio']), "d-M-Y H:i");?></b>
    </div>
<?php endif; ?> -->

<?php if(!$pronosticoRow['activo']): ?>
    <div class="alert alert-warning mb-3" role="alert">
        <i class="fas fa-exclamation-triangle"></i> No puedes capturar tu pronóstico porque <b>te encuentras inactivo</b>, pregunta al administrador(a) de la quiniela (<?=$adminRow['nombre']?> <?=$adminRow['apellido_paterno']?> <?=$adminRow['apellido_materno']?>) para más información.
    </div>
<?php endif; ?>

<table class="table table-striped mt-3 table-responsive">
    <thead>
        <tr>
            <th class="col text-center" scope="col">Local</th>
            <th class="col text-center" scope="col">Marcador</th>
            <th class="col text-center" scope="col">Visitante</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($fixtures as $fixture): ?>
            <?php
                $partido = array();
                foreach($partidos->getResultArray() as $p)
                {
                    if($p['partido'] == $fixture["id"])
                    {
                        $partido = $p;
                        $partido['partido_id'] = util_encode($partido['partido_id']);
                        break;
                    }
                }
                if($pronosticoRow['pronostico_id'] === "37" && !isset($partido['pronostico_local']))
                {
                    $inTime = true;
                }
            ?>
            <?php
            if(date_format(new DateTime($fixture["date"]), "d-M-Y H:i") < date_format(new DateTime(), "d-M-Y H:i"))
            {
                continue;
            }
            ?>
            <tr>
                <td class="align-middle text-center">
                    <table style="width: 100%;">
                        <tr>
                            <td rowspan="2" style="width: 50%;">
                                <?php if($pronosticoRow['activo'] && $inTime && isset($partido['partido_id'])): ?>
                                    <div class="form">
                                        <input name="partido[<?=$partido['partido_id']?>][home]" value="<?=$partido['pronostico_local']?>" type="text" style="width: 100px; font-size: 20px;" class="form-control text-center m-auto" placeholder="" data-validate="number" data-validate-label="<?=$fixture["home_name"]?>">
                                        <input name="partido[<?=$partido['partido_id']?>][partido]" value="<?=util_encode($partido['partido'])?>" type="hidden">
                                    </div>
                                <?php else: ?>
                                    <h3><?=isset($partido['pronostico_local']) ? $partido['pronostico_local'] : "-"?></h3>
                                <?php endif; ?>
                            </td>
                            <td style="width: 50%;">
                                <img src="<?=$fixture["home_logo"]?>" class="card-img-top" style="max-width: 50px; max-height: 50px;">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?=$fixture["home_name"]?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="align-middle text-center">
                    <?php if(is_null($fixture["home_goals"]) || is_null($fixture["away_goals"])): ?>
                        <?=date_format(new DateTime($fixture["date"]), "d-M-Y");?>
                        <br />
                        <?=date_format(new DateTime($fixture["date"]), "H:i");?>
                    <?php else: ?>
                        <?=$fixture["home_goals"]?> - <?=$fixture["away_goals"]?>
                    <?php endif; ?>
                </td>
                <td class="align-middle text-center">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;">
                                <img src="<?=$fixture["away_logo"]?>" class="card-img-top" style="max-width: 50px; max-height: 50px;">
                            </td>
                            <td rowspan="2" style="width: 50%;">
                                <?php if($pronosticoRow['activo'] && $inTime && isset($partido['partido_id'])): ?>
                                    <div class="form">
                                        <input name="partido[<?=$partido['partido_id']?>][away]" value="<?=$partido['pronostico_visitante']?>" type="text" style="width: 100px; font-size: 20px;" class="form-control text-center m-auto" placeholder="" data-validate="number" data-validate-label="<?=$fixture["away_name"]?>">
                                    </div>
                                <?php else: ?>
                                    <h3><?=isset($partido['pronostico_visitante']) ? $partido['pronostico_visitante'] : "-"?></h3>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?=$fixture["away_name"]?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="d-grid gap-2 justify-content-md-end d-md-flex fixed-bottom m-3">
    <button class="btn btn-secondary btnRegresar"><i class="fas fa-angle-left"></i> Regresar</button>
    <?php if($pronosticoRow['activo'] && $inTime): ?>
        <button class="btn btn-alternative btnSave"><i class="fas fa-save"></i> Guardar</button>
    <?php endif; ?>
</div>