<?php
defined('BASEPATH') or exit('No direct script access allowed');

    $nombre         = $this->class_security->validate_var($datas,'pv_nombre');
    $cedula         = $this->class_security->validate_var($datas,'pv_cedula');
    $tipo_invitado  = $this->class_security->validate_var($datas,'pv_tipo_invitado');
    $tipo_vehiculo  = $this->class_security->validate_var($datas,'pv_tipo_vehiculo');
    $placa          = $this->class_security->validate_var($datas,'pv_placa');
    $estado         = $this->class_security->validate_var($datas,'pv_estado');
    $fecha          = $this->class_security->validate_var($datas,'pv_fecha_ingreso');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">

    <div class="modal-body">
        <div class="row mb-3">
            <div class="form-group col-md-5">
                <label>Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?=$nombre?>" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Cedula</label>
                <input type="text" id="cedula" name="cedula" value="<?=$cedula?>" required placeholder="Cedula" autofocus  class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Tipo de invitado</label>
                <select class="form-control text-center imput_reset" onchange="$(this).invitado_unico();" required  name="tipo_invitado" id="tipo_invitado" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach ($this->class_data->tipo_invitado as $ti_id => $ti_val){
                        if($tipo_invitado == $ti_id){
                            echo "<option value='{$ti_id}' selected>{$ti_val}</option>";
                        }else{
                            echo "<option value='{$ti_id}'>{$ti_val}</option>";
                        }
                    }
                    ?>

                </select>
            </div>

        </div>

        <div class="row mb-3">
            <div class="form-group col-md-4">
                <label>Tipo Vehiculo</label>
                <select class="form-control text-center imput_reset"  name="tipo" id="tipo" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach ($this->class_data->autos as $t_id => $t_val){
                        if($tipo_vehiculo == $t_id){
                            echo "<option value='{$t_id}' selected>{$t_val}</option>";
                        }else{
                            echo "<option value='{$t_id}'>{$t_val}</option>";
                        }
                    }
                    ?>

                </select>
            </div>

            <div class="form-group col-md-3">
                <label>Placa</label>
                <input type="text" id="placa" name="placa" value="<?=$placa?>"  placeholder="Placa" autofocus  class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="estado" id="estado" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach ($this->class_data->estado_simple as $e_id => $e_val){
                        $e_titulo = $e_val['title'];
                        if($estado == $e_id){
                            echo "<option value='{$e_id}' selected>{$e_titulo}</option>";
                        }else{
                            echo "<option value='{$e_id}'>{$e_titulo}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-2">
                <label>Fecha Ingreso</label>
                <input type="text" id="modal_ingreso" name="fecha_ingreso" value="<?=$fecha?>"  disabled placeholder="Ingreso" autofocus  class="form-control imput_reset" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col-md-12">
                <label>Imagen</label>
                    <input type="file" name="imagen" accept=".gif,.jpg,.jpeg,.png" class="form-control imput_reset" id="customFile" >
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
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Invitado</button>
    </div>

</form>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-900');
        $(this).fecha_func('#modal_ingreso','yyyy-mm-dd');


        $.fn.invitado_unico = function() {
            return this.each(function(){
                let invitado = $(this).val();
                let cpm_invitado = $("#modal_ingreso");

                if(invitado == 2){
                    cpm_invitado.val('').change().prop('disabled', false);
                    //activar el datetime
                }else{
                    cpm_invitado.val('').change().prop('disabled', true);
                }
                return false;
            })
        }



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