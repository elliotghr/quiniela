        <?php if(in_array('bootstrap', $HTMLModules)): ?>
            <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
        <?php endif; ?>

        <?php if(in_array('jquery', $HTMLModules)): ?>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <?php endif; ?>
        
        <?php if(in_array('logout', $HTMLModules)): ?>
            <script src="/js/general/logout.js"></script>
        <?php endif; ?>

        <?php if(in_array('validate', $HTMLModules)): ?>
            <script src="/js/general/validate.js"></script>
        <?php endif; ?>

        <?php if(in_array('modal', $HTMLModules)): ?>
            <script src="/js/general/modal.js"></script>
        <?php endif; ?>

        <?php if(in_array('upload', $HTMLModules)): ?>
            <script src="/js/general/upload.js"></script>
        <?php endif; ?>

        <?php if(!in_array('noLoading', $HTMLModules)): ?>
            <script src="/js/general/loading.js"></script>
            <?=view('Templates/loading')?>
        <?php endif; ?>

        <?php if(in_array('fontawesome', $HTMLModules)): ?>
            <script src="https://kit.fontawesome.com/c36fc2fe59.js" crossorigin="anonymous"></script>
        <?php endif; ?>

        <?php if(in_array('reCaptchaV2', $HTMLModules) && env('reCaptchaV2.active') === true): ?>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <?php endif; ?>

        <?php if(in_array('reCaptchaV3', $HTMLModules) && env('reCaptchaV3.active') === true): ?>
            <script src="https://www.google.com/recaptcha/api.js?render=<?=env('reCaptchaV3.public')?>"></script>
        <?php endif; ?>

        <?php if(in_array('js', $HTMLModules)): ?>
            <script src="/js/<?=$js?>"></script>
        <?php endif; ?>
    </body>
</html>
