<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-lg-8">

            <div class="shadow bg-white p-4 mb-4 rounded-3">
                <div class="row text-secondary">
                    <div class="col-10">
                        <h3>
                            Roles
                        </h3>
                    </div>
                    <div class="col-2 text-end">
                        <h3>
                            <i class="fas fa-tag"></i>
                        </h3>
                    </div>
                </div>

                <hr />

                <?=$success?>
                <?=$error?>

                <?=$dataTable?>

                <?=$modalDelete?>
                <?=$modalEdit?>
                <?=$modalNew?>

                <div class="text-end">
                    <button id="btnSave" type="button" class="btn btn-alternative" data-action="new">
                        <i class="fas fa-plus"></i>&nbsp;&nbsp;&nbsp;Agregar Rol
                    </button>
                </div>
                
            </div>

        </div>
    </div>
</div>