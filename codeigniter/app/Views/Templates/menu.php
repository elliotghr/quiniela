<nav class="navbar navbar-expand-lg navbar-light navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand me-5" href="/home">
            <i class="<?=env('theme.icon')?>"></i>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach($menuResult as $menu): ?>
                    <?php if(!isset($menu['modulo_padre_id'])): ?>
                        <?php if(isset($menu['url'])): ?>
                            <li class="nav-item">
                                <a class="nav-link me-2 active" aria-current="page" href="/<?=$menu['url']?>">
                                    <i class="fas fa-<?=$menu['icono']?>"></i> <?=$menu['titulo']?>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle me-2 active" href="#" id="<?=$menu['modulo_id']?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-<?=$menu['icono']?>"></i> <?=$menu['titulo']?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="<?=$menu['modulo_id']?>">
                                    <?php foreach($menuResult as $submenu): ?>
                                        <?php if($menu['modulo_id'] == $submenu['modulo_padre_id']): ?>
                                            <li>
                                                <a class="dropdown-item" href="/<?=$submenu['url']?>">
                                                    <div class="row">
                                                        <div class="col-2"><i class="fas fa-<?=$submenu['icono']?> text-secondary"></i></div>
                                                        <div class="col"><?=$submenu['titulo']?></div>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <div class="d-flex">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a id="menuLogout" class="nav-link active" aria-current="page" href="#"><i class="fas fa-sign-out-alt"></i> Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<?php 
    $router = service('router');
    $moduloPadreId = 0;
    $moduloId = 0;
    $padre = null;
    $hijo = null;
    foreach($menuResult as $menu)
    {
        if(str_replace('/(.*)', '', $router->getMatchedRoute()[0]) == $menu['url'])
        {
            if(isset($menu['modulo_padre_id']))
            {
                $moduloPadreId = $menu['modulo_padre_id'];
                $hijo = $menu['titulo'];
                foreach($menuResult as $submenu)
                {
                    if($moduloPadreId == $submenu['modulo_id'])
                    {
                        $padre = $submenu['titulo'];
                        break;
                    }
                }
            }
            else
            {
                $padre = $menu['titulo'];
            }

            break;
        }
    }
?>

<nav class="rounded-0 alert alert-secondary ps-3 pb-2 pt-2 m-0 mb-5" aria-label="breadcrumb" style="--bs-breadcrumb-divider: '>';">
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><?=$padre?></li>
        <?php if(isset($hijo)): ?>
            <li class="breadcrumb-item" aria-current="page"><?=$hijo?></li>
        <?php endif; ?>
    </ol>
</nav>
