<div class="form-floating mb-3">
    <select id="rol" name="rol" class="form-select modal-text" aria-label="Default select example" data-validate="required" data-validate-label="Rol">
        <option value="" selected>-- Selecciona un rol --</option>
        <?php foreach($rols->getResultArray() as $rowRol): ?>
            <option value="<?=$rowRol['id']?>"><?=$rowRol['descripcion']?></option>
        <?php endforeach; ?>
    </select>
    <label for="rol">Rol</label>
</div>
<div class="form-floating mb-3">
    <input id="nombre" name="nombre" type="text" class="form-control modal-text" placeholder="" data-validate="required" data-validate-label="Nombre">
    <label for="nombre">Nombre</label>
</div>
<div class="form-floating mb-3">
    <input id="apellidoPaterno" name="apellidoPaterno" type="text" class="form-control modal-text" placeholder="">
    <label for="apellidoPaterno">Apellido Paterno</label>
</div>
<div class="form-floating mb-3">
    <input id="apellidoMaterno" name="apellidoMaterno" type="text" class="form-control modal-text" placeholder="">
    <label for="apellidoMaterno">Apellido Materno</label>
</div>
<div class="form-floating mb-3">
    <input id="correo" name="correo" type="text" class="form-control modal-text" placeholder="" data-validate="required email" data-validate-label="Correo">
    <label for="correo">Correo</label>
</div>
<div class="form-floating mb-3">
    <input id="fechaNacimiento" name="fechaNacimiento" type="date" class="form-control modal-text" placeholder="" data-validate="date" data-validate-label="Fecha de Nacimiento">
    <label for="fechaNacimiento">Fecha de Nacimiento</label>
</div>
