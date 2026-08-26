<?php if (isset($marcador)): ?>
    <?= $marcador ?>
<?php endif; ?>

<table class="table table-striped mb-5 fs-6">
    <thead>
        <tr>
            <?php if (!$inTime): ?>
                <th class="col text-center" scope="col"></th>
            <?php endif; ?>
            <th class="col text-center d-none d-lg-block" scope="col">&nbsp;</th>
            <th class="col text-center" scope="col">Nombre</th>
            <?php if (isset($marcador)): ?>
                <th class="col text-center" scope="col">Marcador</th>
            <?php endif; ?>
            <th class="col text-center" scope="col">Puntos</th>
        </tr>
    </thead>
    <tbody>
        <?php $cont = 0; ?>
        <?php $aux = null; ?>
        <?php foreach ($participantes as $participante): ?>
            <?php if ($aux !== $participante['puntos']): ?>
                <?php $cont += 1; ?>
                <?php $aux = $participante['puntos']; ?>
            <?php endif; ?>
            <tr>
                <td class="align-middle text-center fs-4">
                    <?= $cont ?>&nbsp;
                    <?php if ($cont <= 3): ?>
                        <i class="fas fa-crown place-<?= $cont ?>"></i>
                    <?php else: ?>
                        <i class="fas fa-medal text-secondary"></i>
                    <?php endif; ?>
                </td>

                <td class="align-middle text-center d-none d-lg-block">
                    <div class="avatar-50px">
                        <div>
                            <img src="<?= os_loadImage('avatar/' . $participante['usuario_avatar']) ?>" alt="avatar" class="mx-auto d-block">
                        </div>
                    </div>
                </td>

                <td class="align-middle text-start">
                    <?= $participante['usuario_nombre'] ?> <?= $participante['usuario_apellido_paterno'] ?> <?= $participante['usuario_apellido_materno'] ?> (<?= $participante['pronostico_consecutivo'] ?>)
                </td>

                <?php if (isset($marcador)): ?>
                    <td class="align-middle text-center">
                        <?php if (isset($participante['partido_pronostico_local']) && isset($participante['partido_pronostico_visitante'])): ?>
                            <?= $participante['partido_pronostico_local'] ?> - <?= $participante['partido_pronostico_visitante'] ?>
                        <?php else: ?>
                            ?
                        <?php endif; ?>
                    </td>
                <?php endif; ?>

                <td class="align-middle text-center">
                    <?= $participante['puntos'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>