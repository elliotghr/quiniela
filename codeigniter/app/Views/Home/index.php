<?php $userRow = $user->getRowArray(); ?>

<div class="container">
    <form id="formQuiniela" method="post" action="#">
        <div class="row">
            <div class="col-lg-7 col-12">
                <div class="shadow  p-4 mb-4 rounded-3">
                    <div class="row text-secondary">
                        <div class="col-10">
                            <h3>
                                <span class="me-3">Próximos partidos</span>
                                <button class="btn btn-primary btnSave"><i class="fas fa-save"></i> Guardar</button>
                            </h3>
                        </div>
                        <div class="col-2 text-end">
                            <h3>
                                <i class="far fa-calendar-alt"></i>
                            </h3>
                        </div>
                    </div>

                    <hr />
                    
                    <div class="table-container" style="max-height: 500px; overflow-y: auto;">
                        <?= $upcomingFixtures; ?>
                    </div>
                </div>
            </div>



            <div class="col-lg-5 col-12">
                <div class="shadow  p-4 mb-4 rounded-3">
                    <div class="row text-secondary">
                        <div class="col-10">
                            <h3>
                                Últimos Marcadores 
                            </h3>
                        </div>
                        <div class="col-2 text-end">
                            <h3>
                                <i class="fas fa-poll"></i>
                            </h3>
                        </div>
                    </div>

                    <hr />
                    
                    <div class="table-container" style="max-height: 500px; overflow-y: auto;">
                        <?= $lastResults; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?=$success?>
<?=$error?>