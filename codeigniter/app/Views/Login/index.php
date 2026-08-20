<div class="container">
    <form id="formData" method="post" action ="#">
        <div class="row justify-content-md-center">
            <div class="col-md-auto mt-5">
                <div class="card loginContainer">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Ingresar</h4>
                    </div>
                    <div class="card-body m-3">
                        <div class="input-group mb-3 text-center">
                            <?php if(env("theme.logo") === true): ?>
                                <img src="/assets/logo.png" class="m-auto" style="max-width: 200px; max-height: 200px;">
                            <?php endif; ?>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text alert-primary"><i class="fas fa-user"></i></span>
                            <input id="user" name="user" type="text" class="form-control" placeholder="Usuario">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text alert-primary"><i class="fas fa-key"></i></span>
                            <input id="password" name="password" type="password" class="form-control" placeholder="Contraseña">
                        </div>
                        <?php if(env('reCaptchaV2.active') === true): ?>
                            <div class="input-group mb-3">
                                <div id="captchaLogin" class="g-recaptcha" data-sitekey="<?=env('reCaptchaV2.public')?>"></div>
                            </div>
                        <?php endif; ?>
                        <?php if(env('reCaptchaV3.active') === true): ?>
                            <input id="captchaLogin_v3" type="hidden" data-sitekey="<?=env('reCaptchaV3.public')?>">
                        <?php endif; ?>
                        <div class="text-end mb-3">
                            <button id="btnLogin" type="button" class="btn btn-alternative">
                                <i class="fas fa-sign-in-alt"></i>&nbsp;&nbsp;&nbsp;Ingresar
                            </button>
                        </div>
                        <div class="text-end">
                            <a href="#" class="link-secondary" data-action="reset">Recuperar contraseña</a>
                        </div>
                        <?php if(env('registro.modulo') === true): ?>
                            <div class="text-end">
                                <a href="#" class="link-secondary" data-action="new">Quiero registrarme</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?=$success?>
    <?=$error?>
    <?=$modalResetPassword?>
    <?php if(env('registro.modulo') === true): ?>
        <?=$modalNew?>
    <?php endif; ?>
</div>
