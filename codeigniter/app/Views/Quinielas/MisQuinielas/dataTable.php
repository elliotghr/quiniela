<div class="shadow bg-white p-4 mb-4 rounded-3">
    <div class="row text-secondary">
        <div class="col-10">
            <h3>
                <?=$subTitle?> <span id="alertSave" class="badge rounded-pill bg-warning text-dark fs-6" style="display: none;">Sin Guardar</span>
            </h3>
        </div>
        <div class="col-2 text-end">
            <h3>
                <i class="fas fa-tasks"></i>
            </h3>
        </div>
    </div>

    <hr />

    <?php if(isset($formNuevaQuiniela)): ?>
    <ul class="nav nav-pills nav-justified mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="#" data-main-tabs="misQuinielas">
                <i class="fas fa-list me-1"></i> Mis Quinielas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-main-tabs="nuevaQuiniela">
                <i class="fas fa-plus me-1"></i> Nueva Quiniela
            </a>
        </li>
    </ul>

    <div id="divMisQuinielas">
        <?=$mainTable?>
    </div>
    <div id="divNuevaQuiniela" style="display: none;">
        <?=$formNuevaQuiniela?>
    </div>
    <?php else: ?>
    <?=$mainTable?>
    <?php endif; ?>
</div>





