<table id="dataTable" class="table table-striped mt-3">
    <thead>
        <tr>
            <th class="col" scope="col">Descripcion</th>
            <th class="col-2 text-center" scope="col">Editar</th>
            <th class="col-2 text-center" scope="col">Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($rols->getResultArray() as $rowRol): ?>
            <tr>
                <td><?=$rowRol['descripcion']?></td>
                <td class="text-center">
                    <a href="#" class="link-secondary" title="Editar" data-action="edit" data-id="<?=$rowRol['id']?>" data-value="<?=$rowRol['descripcion']?>">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
                <td class="text-center">
                    <a href="#" class="link-secondary" title="Eliminar" data-action="delete" data-id="<?=$rowRol['id']?>" data-value="<?=$rowRol['descripcion']?>">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>