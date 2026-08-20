<table id="dataTable" class="table table-striped mt-3">
    <thead>
        <tr>
            <th class="col align-top" scope="col">Módulo</th>
            <th class="col-2 text-center" scope="col">Permitido<br /><input id="accessAll" name="" type="checkbox" data-type="access"></th>
            <th class="col-2 text-center" scope="col">Escritura<br /><input id="writeAll" name="" type="checkbox" data-type="write"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($modulos as $modulo): ?>
            <?php if(!isset($modulo['modulo_padre_id'])): ?>
                <?php
                    $encontrado = false;
                    $escritura = false;
                    foreach($accesos as $acceso):
                        if($acceso['modulo_id'] == $modulo['id']):
                            $encontrado = true;
                            $escritura = $acceso['modulo_rol_escritura'] == 1 ? true : false;
                        endif;
                    endforeach;
                ?>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-1 text-end text-secondary"><i class="fas fa-<?=$modulo['icono']?>"></i></div>
                            <div class="col"><?=$modulo['titulo']?></div>
                        </div>
                    </td>
                    <td class="text-center">
                        <input id="access-<?=$modulo['id']?>" name="access[<?=$modulo['id']?>][access]" type="checkbox" data-type="access" <?=$encontrado ? 'checked="checked"' : ''?>>
                    </td>
                    <td class="text-center">
                        <input id="write-<?=$modulo['id']?>" name="access[<?=$modulo['id']?>][write]" type="checkbox" data-type="write" <?=$escritura ? 'checked="checked"' : ''?>>
                    </td>
                </tr>
                
                <?php foreach($modulos as $subModulo): ?>
                    <?php if($modulo['id'] == $subModulo['modulo_padre_id']): ?>
                        <?php
                            $encontrado = false;
                            $escritura = false;
                            foreach($accesos as $acceso):
                                if($acceso['modulo_id'] == $subModulo['id']):
                                    $encontrado = true;
                                    $escritura = $acceso['modulo_rol_escritura'] == 1 ? true : false;
                                endif;
                            endforeach;
                        ?>
                        <tr>
                            <td class="ps-5">
                                <div class="row">
                                    <div class="col-1 text-end text-secondary"><i class="fas fa-<?=$subModulo['icono']?>"></i></div>
                                    <div class="col"><?=$subModulo['titulo']?></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <input id="access-<?=$subModulo['id']?>" name="access[<?=$subModulo['id']?>][access]" type="checkbox" data-type="access" <?=$encontrado ? 'checked="checked"' : ''?>>
                            </td>
                            <td class="text-center">
                                <input id="write-<?=$subModulo['id']?>" name="access[<?=$subModulo['id']?>][write]" type="checkbox" data-type="write" <?=$escritura ? 'checked="checked"' : ''?>>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>