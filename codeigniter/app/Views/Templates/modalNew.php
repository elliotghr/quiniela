<div class="modal fade" id="modalNew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Agregar</h5>
                <i class="modal-close fas fa-times text-white" data-bs-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body">
                <form id="formNew" class="modal-form" action="#" method="post">
                    <?=$formNew?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;&nbsp;Cancelar</button>
                <button type="button" class="btn btn-alternative modal-save"><i class="fas fa-plus"></i>&nbsp;&nbsp;&nbsp;Agregar</button>
            </div>
        </div>
    </div>
</div>
