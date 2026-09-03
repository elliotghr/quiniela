<form id="formNuevaQuiniela" method="post" action="#">
    <input type="hidden" id="quinielaId" name="quinielaId" value="">

    <h5 class="text-secondary mb-3" id="tituloFormQuiniela">
        <i class="fas fa-plus me-2"></i>Nueva Quiniela
    </h5>

    <div class="form-floating mb-3">
        <select id="ligaId" name="ligaId" class="form-select modal-text" data-validate="required" data-validate-label="Liga">
            <option value="">-- Selecciona una liga --</option>
            <?php foreach($leagues as $id => $league): ?>
                <option value="<?=$id?>"><?=$league['name']?></option>
            <?php endforeach; ?>
        </select>
        <label for="ligaId">Liga</label>
    </div>

    <div class="form-floating mb-3">
        <input id="temporada" name="temporada" type="text" class="form-control modal-text" placeholder="" data-validate="required" data-validate-label="Temporada" value="<?=date('Y')?>">
        <label for="temporada">Temporada</label>
    </div>

    <div class="form-floating mb-3">
        <input id="nombre" name="nombre" type="text" class="form-control modal-text" placeholder="" data-validate="required" data-validate-label="Nombre">
        <label for="nombre">Nombre de la Quiniela</label>
    </div>

    <div class="form-floating mb-3">
        <input id="fechaInicio" name="fechaInicio" type="datetime-local" class="form-control modal-text" placeholder="" data-validate="required" data-validate-label="Fecha límite">
        <label for="fechaInicio">Fecha límite de captura</label>
    </div>

    <div class="form-floating mb-4">
        <input id="maxPronosticos" name="maxPronosticos" type="number" class="form-control modal-text" placeholder="" min="1" max="10" value="1" data-validate="required number" data-validate-label="Máximo de pronósticos">
        <label for="maxPronosticos">Máximo de pronósticos por participante</label>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="button" class="btn btn-outline-danger btnEliminarQuiniela me-auto" style="display: none;">
            <i class="fas fa-trash me-1"></i>Eliminar
        </button>
        <button type="button" class="btn btn-outline-secondary btnResetFormQuiniela me-2" style="display: none;">
            <i class="fas fa-times me-1"></i>Cancelar
        </button>
        <button type="button" class="btn btn-primary btnNuevaQuiniela">
            <i class="fas fa-save me-2"></i><span id="btnSubmitTexto">Crear Quiniela</span>
        </button>
    </div>

</form>
