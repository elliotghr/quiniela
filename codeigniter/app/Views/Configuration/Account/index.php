<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-lg-6">
            <form id="formAvatar" method="post" action ="#" enctype="multipart/form-data">
                <div class="shadow bg-white p-4 mb-4 rounded-3">
                    <div class="row text-secondary">
                        <div class="col-10">
                            <h3>
                                Datos Personales
                            </h3>
                        </div>
                        <div class="col-2 text-end">
                            <h3>
                                <i class="fas fa-user"></i>
                            </h3>
                        </div>
                    </div>

                    <hr />

                    <?php $userRow = $user->getRowArray() ?>
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="avatar-150px border">
                                <div>
                                    <img id="imgAvatar" src="<?=os_loadImage('avatar/' . $userRow['avatar'])?>" alt="avatar" class="mx-auto d-block">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 text-center">
                            <label for="fileAvatar" class="btn btn-light btnInputFile">
                            <i class="fas fa-upload"></i> Cambiar Imagen
                            </label>
                            <input type="file" name="fileAvatar" id="fileAvatar" class="input-file" accept=".jpg, .png">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 text-end text-secondary">Usuario:</div>
                        <div class="col"><?=$userRow['usuario']?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 text-end text-secondary">Rol:</div>
                        <div class="col"><?=$userRow['rol']?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 text-end text-secondary">Nombre:</div>
                        <div class="col"><?=$userRow['nombre']?> <?=$userRow['apellido_paterno']?> <?=$userRow['apellido_materno']?></div>
                    </div>
                    
                </div>
            </form>
            <form id="formData" method="post" action ="#">
                <div class="shadow bg-white p-4 mb-4 rounded-3">

                    <div class="row text-secondary">
                        <div class="col-10">
                            <h3>
                                Modificar Contraseña
                            </h3>
                        </div>
                        <div class="col-2 text-end">
                            <h3>
                                <i class="fas fa-key"></i>
                            </h3>
                        </div>
                    </div>

                    <hr />
                
                    <?php if(!$changePassword): ?>
                        <div class="form-floating mb-3">
                            <input name="oldPassword" type="password" class="form-control" placeholder="" data-validate="required" data-validate-label="Contraseña anterior">
                            <label for="floatingInput">Contraseña anterior</label>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating mb-3">
                                <input name="newPassword" type="password" class="form-control" placeholder="" data-validate="required password" data-validate-label="Nueva contraseña">
                                <label for="floatingInput">Nueva contraseña</label>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-floating mb-3">
                                <input name="newPasswordBis" type="password" class="form-control" placeholder="" data-validate="required equal" data-validate-equal="newPassword" data-validate-label="Repetir nueva contraseña">
                                <label for="floatingInput">Repetir contraseña</label>
                            </div>
                        </div>
                    </div>
            
                    <div class="text-end">
                        <button id="btnSave" type="button" class="btn btn-alternative">
                            <i class="fas fa-save"></i>&nbsp;&nbsp;&nbsp;Modificar Contraseña
                        </button>
                    </div>

                </div>
            </form>
            <?=$success?>
            <?=$error?>
        </div>
    </div>
</div>