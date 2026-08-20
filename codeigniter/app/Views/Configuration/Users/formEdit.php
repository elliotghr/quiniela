<input id="usuarioId" name="usuarioId" type="hidden" value="<?=$usuario['usuario_id']?>">
<input id="datosUsuarioId" name="datosUsuarioId" type="hidden" value="<?=$usuario['datos_usuario_id']?>">
<div class="form-floating mb-3">
    <select id="rol" name="rol" class="form-select modal-text" aria-label="Default select example" data-validate="required" data-validate-label="Rol">
        <option value="" selected>-- Selecciona un rol --</option>
        <?php foreach($rols->getResultArray() as $rowRol): ?>
            <?php if($rowRol['id'] == $usuario['rol_id']): ?>
                <option value="<?=$rowRol['id']?>" selected="selected"><?=$rowRol['descripcion']?></option>
            <?php else: ?>
                <option value="<?=$rowRol['id']?>"><?=$rowRol['descripcion']?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
    <label for="rol">Rol</label>
</div>
<div class="form-floating mb-3">
    <input id="nombre" name="nombre" value="<?=$usuario['nombre']?>" type="text" class="form-control modal-text" placeholder="" data-validate="required" data-validate-label="Nombre">
    <label for="nombre">Nombre</label>
</div>
<div class="form-floating mb-3">
    <input id="apellidoPaterno" name="apellidoPaterno" value="<?=$usuario['apellido_paterno']?>" type="text" class="form-control modal-text" placeholder="">
    <label for="apellidoPaterno">Apellido Paterno</label>
</div>
<div class="form-floating mb-3">
    <input id="apellidoMaterno" name="apellidoMaterno" value="<?=$usuario['apellido_materno']?>" type="text" class="form-control modal-text" placeholder="">
    <label for="apellidoMaterno">Apellido Materno</label>
</div>
<div class="form-floating mb-3">
    <input id="correo" name="correo" value="<?=$usuario['correo']?>" type="text" class="form-control modal-text" placeholder="" data-validate="required email" data-validate-label="Correo">
    <label for="correo">Correo</label>
</div>
<div class="form-floating mb-3">
    <input id="fechaNacimiento" name="fechaNacimiento" value="<?=$usuario['fecha_nacimiento']?>" type="date" class="form-control modal-text" placeholder="" data-validate="date" data-validate-label="Fecha de Nacimiento">
    <label for="fechaNacimiento">Fecha de Nacimiento</label>
</div>
