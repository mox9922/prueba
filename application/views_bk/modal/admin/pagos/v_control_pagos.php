<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">

    <div class="modal-body">

        <div class="row mb-2">

            <div class="form-group col-md-4">
                <label class="text-center">Total M3</label>
                <input type="text" class="form-control text-center numeros imput_reset" placeholder="0" required  name="total" id="total" autoCompete="off" onchange="$(this).calcular()">
            </div>

            <div class="form-group col-md-4">
                <label class="text-center">Monto total</label>
                <input type="text" class="form-control text-center dinero imput_reset" placeholder="₡ 0" required name="monto" id="monto" autoCompete="off" onchange="$(this).calcular()">
            </div>

            <div class="form-group col-md-4">
                <label class="text-center">total M3</label>
                <input type="text" class="form-control dinero text-center " value="0" name="m3" id="m3" readonly autoCompete="off">
            </div>

        </div>

        <div class="row mb-2">

            <div class="form-group col-md-6">
                <label class="text-center">Mes</label>
                <select class="form-control text-center form-select imput_reset"  required name="mes" autoCompete="off">
                    <option value="">[ SELECCCIONAR ]</option>
                    <?php
                    foreach($this->class_data->meses As $me_id => $me_val){
                        echo "<option value='{$me_id}'>{$me_val}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label class="text-center">Año</label>
                <input type="text" value="<?=date("Y")?>" readonly class="form-control text-center select " required name="anno" autoCompete="off">

            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Ventana</button>
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
    </div>

</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-600','.in_modal_secundario');

        // $(this).clear_form();
        // $(this).select2_func('#apartamento');
        // $(this).fecha_min_func('#fecha');
        $(this).dinero_func('.dinero');
        $(this).numeros_func('#numeros');

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