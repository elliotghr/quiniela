<h4 class="text-secondary">
    <i class="fas fa-users"></i> Participantes
</h4>
<div class="alert alert-info mt-3 mb-3" role="alert">
    Los participantes inactivos no podrán capturar sus pronósticos.
</div>
<table class="table table-striped mb-5">
    <thead>
        <tr>
            <th class="col text-center" scope="col"></th>
            <th class="col text-center" scope="col"></th>
            <th class="col text-center" scope="col">Nombre</th>
            <th class="col text-center" scope="col">Correo</th>
            <th class="col text-center" scope="col">Pronóstico</th>
            <th class="col text-center" scope="col">Activo</th>
        </tr>
    </thead>
    <tbody>
    <?php $cont = 0; ?>
    <?php foreach($participantes->getResultArray() as $participante): ?>
        <?php $cont += 1; ?>
        <tr>
            <td class="align-middle text-center">
                <?=$cont?>
            </td>

            <td class="align-middle text-center">
                <div class="avatar-50px">
                    <div>
                        <img src="<?=os_loadImage('avatar/' . $participante['usuario_avatar'])?>" alt="avatar" class="mx-auto d-block">
                    </div>
                </div>
            </td>
        
            <td class="align-middle text-center">
                <?=$participante['usuario_nombre']?> <?=$participante['usuario_apellido_paterno']?> <?=$participante['usuario_apellido_materno']?>
            </td>

            <td class="align-middle text-center">
                <?=$participante['usuario_usuario']?>
            </td>

            <td class="align-middle text-center">
                <?=$participante['pronostico_consecutivo']?>
            </td>
        
            <td class="align-middle text-center">
                <div class="m-auto" style="width: 32px;">
                    <div class="form-check form-switch">
                        <input name="pronostico[<?=util_encode($participante['pronostico_id'])?>]" class="form-check-input admCheck" type="checkbox" role="switch" value="<?=util_encode($participante['pronostico_id'])?>" <?=$participante['pronostico_activo'] ? 'checked' : '' ?>>
                    </div>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div>
    <h4 class="text-secondary">
        <i class="fas fa-user-plus"></i> Agregar Participantes
    </h4>
    <div class="alert alert-info mt-3 mb-3" role="alert">
        Comparte la siguiente liga para agregar más participantes:
    </div>
    <p style="font-size: 14px; overflow-wrap: break-word;" class="text-secondary text-wrap text mt-3 p-3 border">
        <a href="<?=$url?>" target="_blank"><?=$url?></a>
    </p>
</div>
