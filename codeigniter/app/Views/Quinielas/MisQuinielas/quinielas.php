<div class="d-none d-lg-block">
    <table id="dataTable" class="table table-striped mt-3 table-hover table-responsive">
        <thead>
            <tr>
                <th class="col" scope="col"></th>
                <th class="col" scope="col">Liga</th>
                <th class="col" scope="col">Temporada</th>
                <th class="col" scope="col">Jornada</th>
                <th class="col" scope="col">Inicio</th>
                <th class="col" scope="col">Fin</th>
                <th class="col" scope="col">Fecha limite de captura</th>
                <th class="col" scope="col"></th>
            </tr>
        </thead>
        <tbody style="cursor: pointer;">
            <?php foreach ($quinielas->getResultArray() as $rowQuiniela): ?>
                <tr data-action="viewQuiniela" data-id="<?= $rowQuiniela['quiniela_id'] ?>">
                    <td class="align-middle p-3"><img src="<?= $leagues[$rowQuiniela["liga"]]["logo"] ?>" class="card-img-top" style="max-width: 75px; max-height: 75px;"></td>
                    <td class="align-middle"><?= $leagues[$rowQuiniela["liga"]]["name"] ?></td>
                    <td class="align-middle"><?= $rowQuiniela['temporada'] ?></td>
                    <td class="align-middle"><?= date_format(date_create($leagues[$rowQuiniela["liga"]]["start"]), "d/M") ?></td>
                    <td class="align-middle"><?= date_format(date_create($leagues[$rowQuiniela["liga"]]["end"]), "d/M") ?></td>
                    <td class="align-middle"><?= date_format(date_create($rowQuiniela['fecha_inicio']), "d/M") ?></td>
                    <td class="align-middle text-center">
                        <?php if ($rowQuiniela['quiniela_usuario_id'] == getUserSession()): ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary btnEditQuiniela" data-action="editQuiniela" data-id="<?= util_encode($rowQuiniela['quiniela_id']) ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="d-block d-lg-none">
    <?php foreach ($quinielas->getResultArray() as $rowQuiniela): ?>
        <div class="card mb-3">
            <h2 class="card-header">
                <?= $leagues[$rowQuiniela["liga"]]["name"] ?> - <?= $rowQuiniela['temporada'] ?>
            </h2>
            <div class="card-body">
                <div class="accordion-body">
                    <div class="d-flex justify-content-between align-items-center mb-1 gap-3">
                        <div class="d-flex justify-content-start align-items-center gap-2">
                            <img src="<?= $leagues[$rowQuiniela["liga"]]["logo"] ?>" class="card-img-top" style="max-width: 80px; max-height: 80px;">
                        </div>
                        <div class="d-flex flex-column justify-content-start align-items-start mb-1 gap-1">
                            <p class="mb-0"><strong>Inicio:</strong> <?= date_format(date_create($leagues[$rowQuiniela["liga"]]["start"]), "d-M-Y") ?></p>
                            <p class="mb-0"><strong>Fin:</strong> <?= date_format(date_create($leagues[$rowQuiniela["liga"]]["end"]), "d-M-Y") ?></p>
                        </div>
                    </div>
                    <p class="text-end">
                        <?php if ($rowQuiniela['quiniela_usuario_id'] == getUserSession()): ?>
                            <button type="button" class="btn btn-outline-secondary mt-2 me-2 btnEditQuiniela" data-action="editQuiniela" data-id="<?= util_encode($rowQuiniela['quiniela_id']) ?>">
                                <i class="fa-solid fa-edit me-1"></i>Editar
                            </button>
                        <?php endif; ?>
                        <button id="btnTarjeta" class="btn btn-primary text-white mt-2" data-action="viewQuiniela" data-id="<?= $rowQuiniela['quiniela_id'] ?>"><i class="fa-solid fa-table-list me-3"></i>Ver Quiniela</button>
                    </p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>