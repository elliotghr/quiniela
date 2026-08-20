<table id="dataTable" class="table table-striped mt-3">
    <thead>
        <tr>
            <th class="col" scope="col"></th>
            <th class="col" scope="col">Nombre</th>
            <th class="col" scope="col">Usuario</th>
            <th class="col" scope="col">Rol</th>
            <th class="col text-center" scope="col">Editar</th>
            <th class="col text-center" scope="col">Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users->getResultArray() as $rowUser): ?>
            <tr>
                <td>
                    <div class="avatar-50px border">
                        <div>
                            <img id="imgAvatar" src="<?=os_loadImage('avatar/' . $rowUser['avatar'])?>" alt="avatar" class="mx-auto d-block">
                        </div>
                    </div>
                </td>
                <td class="align-middle">
                    <?=$rowUser['nombre']?> <?=$rowUser['apellido_paterno']?> <?=$rowUser['apellido_materno']?>
                </td>
                <td class="align-middle">
                    <?=$rowUser['usuario']?>
                </td>
                <td class="align-middle">
                    <?=$rowUser['rol']?>
                </td>
                <td class="text-center align-middle">
                    <a href="#" class="link-secondary" title="Editar" data-action="edit" data-id="<?=$rowUser['usuario_id']?>">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
                <td class="text-center align-middle">
                    <a href="#" class="link-secondary" title="Eliminar" data-action="delete" data-id="<?=$rowUser['usuario_id']?>" data-value="<?=$rowUser['nombre']?> <?=$rowUser['apellido_paterno']?> <?=$rowUser['apellido_materno']?> (<?=$rowUser['rol']?>)">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>