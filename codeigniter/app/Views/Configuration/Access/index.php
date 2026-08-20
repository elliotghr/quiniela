<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-lg-10">

            <div class="shadow bg-white p-4 mb-4 rounded-3">
                <div class="row text-secondary">
                    <div class="col-10">
                        <h3>
                            Accesos
                        </h3>
                    </div>
                    <div class="col-2 text-end">
                        <h3>
                            <i class="fas fa-shield-alt"></i>
                        </h3>
                    </div>
                </div>

                <hr />
                
                <form id="formEdit" action="#" method="post">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating mb-3">
                                <select id="rol" name="rol" class="form-select modal-text" aria-label="Default select example" data-validate="required" data-validate-label="Rol">
                                    <option value="" selected>-- Selecciona un rol --</option>
                                    <?php foreach($rols->getResultArray() as $rowRol): ?>
                                        <option value="<?=$rowRol['id']?>"><?=$rowRol['descripcion']?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="rol">Rol</label>
                            </div>
                        </div>
                        <div class="col text-end align-middle" style="display: table-cell">
                            <button id="btnSave" type="button" class="btn btn-alternative">
                                <i class="fas fa-save"></i>&nbsp;&nbsp;&nbsp;Guardar Accesos
                            </button>
                        </div>
                    </div>
                    
                    <div id="dataTable"></div>
                </form>

                <?=$success?>
                <?=$error?>
                
            </div>

        </div>
    </div>
</div>