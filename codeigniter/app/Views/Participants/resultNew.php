<div class="shadow bg-white p-4 mb-4 mt-4 rounded-3">
    <div class="form-floating mb-3 text-center">
        <?php if(env("theme.logo") === true): ?>
            <img src="/assets/logo.png" class="m-auto" style="max-width: 200px; max-height: 200px;">
        <?php endif; ?>
    </div>

    <div class="form-floating mb-3 text-center">
        <div class="alert alert-primary text-start" role="alert">
            <i class="fas fa-check"></i> Tu quiniela fue agregada con éxito.
        </div>
        <div class="alert alert-info text-start" role="alert">
            <i class="fas fa-exclamation-circle"></i> Te hemos enviado un correo electrónico a <b><?=$correo?></b> con las indicaciones para ingresar a Quinieland.
        </div>
    </div>
</div>