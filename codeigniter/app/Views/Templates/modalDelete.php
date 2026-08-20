<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <input class="modal-hdn" type="hidden" />
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formDelete" class="modal-form" action="#" method="post">
                    <input id="id" name="id" type="hidden" value="" />
                </form>
                <div class="modal-text"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;&nbsp;No</button>
                <button type="button" class="btn btn-danger modal-yes"><i class="fas fa-trash"></i>&nbsp;&nbsp;&nbsp;Si</button>
            </div>
        </div>
    </div>
</div>