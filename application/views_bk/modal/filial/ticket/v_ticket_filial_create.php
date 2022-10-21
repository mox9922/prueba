<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">

    <div class="modal-body">
        <div class="row mb-3">
            <div class="form-group col-md-9">
                <label>Titulo Ticket</label>
                <input type="text"name="titulo"  placeholder="Titulo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
            <label>Prioridad</label>
            <select class="form-control text-center imput_reset" required name="prioridad" id="prioridad" autocomplete="off">
                <option value=""> [ SELECCIONAR ] </option>
                <?php
                foreach ($this->class_data->ticket_prioridad as $e_id => $e_val){
                        echo "<option value='{$e_id}'>{$e_val[title]}</option>";
                    }
                ?>
            </select>
        </div>

        </div>

        <div class="row mb-3">

            <div class="form-group col-md-12">
                <label>Descripción</label>
                <textarea  id="descripcion" name="descripcion" rows="5" placeholder="descripcion" autofocus  class="form-control imput_reset" autocomplete="off"></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col-md-6">
                <label>Evidencia 1</label>
                <input type="file" name="documento_1"  accept=".gif,.jpg,.jpeg,.png,.xls,.doc,.docx,.xlsx,.xls,doc,docx,xlsx" class="form-control imput_reset" id="customFile" >
            </div>

            <div class="form-group col-md-6">
                <label>Evidencia 2</label>
                <input type="file" name="documento_2" accept=".gif,.jpg,.jpeg,.png,.xls,.doc,.docx,.xlsx" class="form-control imput_reset" id="customFile" >
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col-md-6">
                <label>Evidencia 3</label>
                <input type="file" name="documento_3" accept=".gif,.jpg,.jpeg,.png,.xls,.doc,.docx,.xlsx" class="form-control imput_reset" id="customFile" >
            </div>

            <div class="form-group col-md-6">
                <label>Evidencia 4</label>
                <input type="file" name="documento_4" accept=".gif,.jpg,.jpeg,.png,.xls,.doc,.docx,.xlsx" class="form-control imput_reset" id="customFile" >
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col-md-12">
                <div class="progress" style="height: 26px;">
                    <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar_invitado" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Ticket</button>
    </div>

</form>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-900');



        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save');
                return false;
            }
        })

    })
</script>