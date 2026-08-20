<div class="shadow bg-white p-4 mb-4 mt-4 rounded-3">
    <div class="form-floating mb-3 text-center">
        <?php if(env("theme.logo") === true): ?>
            <img src="/assets/logo.png" class="m-auto" style="max-width: 200px; max-height: 200px;">
        <?php endif; ?>
    </div>
    <?php if($errorId): ?>
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle"></i> El formulario no es válido, por favor revisar la URL.
        </div>
    <?php else: ?>
        <form id="formParticipant" method="post" action ="#">
            <input type="hidden" name="quinielaId" value="<?=$quiniela_id?>">

            <div class="form-floating mb-3 text-center">
                <div class="alert alert-primary text-start" role="alert">
                    <ul>
                        <li>Has sido invitado a participar en una quiniela, llena el siguiente formulario para agregarte.</li>
                        <li>Si ya eres usuario de quinieland, usa el correo con el que te diste de alta.</li>
                    </ul>
                </div>
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
                <select id="pronosticos" name="pronosticos" class="form-select modal-text" aria-label="Default select example" data-validate="required" data-validate-label="Quinielas">
                    <?php for($cont = 1; $cont <= $max_pronosticos; $cont++): ?>
                        <option value="<?=util_encode($cont )?>"><?=$cont?></option>
                    <?php endfor; ?>
                </select>
                <label for="rol">Cantidad de quinielas que jugarás</label>
            </div>

            <?php if(env('reCaptchaV2.active') === true): ?>
                <div class="input-group mb-3">
                    <div id="captchaLogin" class="g-recaptcha" data-sitekey="<?=env('reCaptchaV2.public')?>"></div>
                </div>
            <?php endif; ?>
            <?php if(env('reCaptchaV3.active') === true): ?>
                <input id="captchaLogin_v3" type="hidden" data-sitekey="<?=env('reCaptchaV3.public')?>">
            <?php endif; ?>

            <div class="d-grid gap-2 justify-content-md-end d-md-flex">
                <button class="btn btn-alternative btnSave"><i class="fas fa-user-plus"></i> Participar</button>
            </div>

            
        </form>
    <?php endif; ?>
</div>
