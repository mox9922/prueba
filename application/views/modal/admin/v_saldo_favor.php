<?php
defined('BASEPATH') or exit('No direct script access allowed');

$apartamento_id    = $this->class_security->validate_var($data,'sf_apartamento');
$apartamento       = $this->class_security->validate_var($data,'t_apartamento');
$saldo             = $this->class_security->validate_var($data,'favor');
?>


<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">

    <div class="modal-body">

        <div class="row  text-center mb-3">

            <div class="form-group col-md-5">
                <label>Filial</label>
                <?php
                if($apartamento_id  == '' ){
                    echo ' <select  name="apartamento"  id="apartamento"  autofocus style="width:100%" class="form-control custom-select " autocomplete="off"><option value=""> [ SELECCIONAR ] </option>';
                    foreach($apartamentos As $apt){
                        echo "<option value='{$apt->t_id}'>{$apt->t_apartamento}</option>";
                    }
                    echo '</select>';
                }else{
                    echo "<input type='hidden' name='apartamento' value='$apartamento_id'> <input type='text' value='{$apartamento}' disabled  class='form-control text-center'>";
                }
                ?>
                <div id="myModal"></div>
            </div>

            <div class="form-group col-md-7">
                <label>Saldo a favor</label>
                <input type="text" name="saldo" value="<?=$saldo?>"  class="form-control text-center saldo " autocomplete="off">
            </div>


        </div>


    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
    </div>

</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-500');
        $(this).dinero_func('.saldo');
        $(this).select2_func('#apartamento');


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