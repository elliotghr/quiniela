<?php $userRow = $user->getRowArray(); ?>
<?php $mostrar = env('quiniela.home.mostrar'); ?>
<div class="container">
    <form id="formData" method="post" action ="#">
        <div class="alert alert-primary mt-3 mb-4" role="alert">
            Hola <?=$userRow['nombre']?>
        </div>
        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="shadow bg-white p-4 mb-4 rounded-3">
                    <div class="row text-secondary">
                        <div class="col-10">
                            <h3>
                                Próximos Eventos
                            </h3>
                        </div>
                        <div class="col-2 text-end">
                            <h3>
                                <i class="far fa-calendar-alt"></i>
                            </h3>
                        </div>
                    </div>

                    <hr />
                
                    <?php $next = 0; ?>
                    <?php $prev = 0; ?>
                    <?php $stopPrev = false; ?>
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th class="col text-center" scope="col" style="width: 35%;">Local</th>
                                <th class="col text-center" scope="col" style="width: 30%;">Horario</th>
                                <th class="col text-center" scope="col" style="width: 35%;">Visitante</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($fixtures as $fixture): ?>
                                <?php if($fixture['date'] >= date('c') && $next < $mostrar): ?>
                                    <tr>
                                        <td class="align-middle text-center">
                                            <table style="width: 100%;">
                                                <tr>
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
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?=$fixture["away_name"]?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <?php $next++; ?>
                                    <?php $stopPrev = true; ?>
                                <?php elseif(!$stopPrev): ?>
                                    <?php $prev++; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if($next === 0): ?>
                                No hay eventos que mostrar
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="shadow bg-white p-4 mb-4 rounded-3">
                    <div class="row text-secondary">
                        <div class="col-10">
                            <h3>
                                Últimos Marcadores
                            </h3>
                        </div>
                        <div class="col-2 text-end">
                            <h3>
                                <i class="fas fa-poll"></i>
                            </h3>
                        </div>
                    </div>

                    <hr />

                    <?php if($prev === 0): ?>
                        No hay marcadores que mostrar
                    <?php else: ?>
                        <table class="table table-striped mt-3">
                            <thead>
                                <tr>
                                    <th class="col text-center" scope="col" style="width: 35%;">Local</th>
                                    <th class="col text-center" scope="col" style="width: 30%;">Horario</th>
                                    <th class="col text-center" scope="col" style="width: 35%;">Visitante</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $from = ($prev - $mostrar + 1) <= 0 ? 1 : ($prev - $mostrar + 1); ?>
                                <?php $to = $prev; ?>
                                <?php $cont = count($fixtures); ?>
                                <?php foreach(array_reverse($fixtures) as $fixture): ?>
                                    <?php if($cont >= $from && $cont <= $to): ?>
                                        <tr>
                                            <td class="align-middle text-center">
                                                <table style="width: 100%;">
                                                    <tr>
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
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <?=$fixture["away_name"]?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php $cont--; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>
