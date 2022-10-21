<?php
defined('BASEPATH') or exit('No direct script access allowed');

    $nombre      =  $this->class_security->validate_var($datas,'u_nombre');
    $tipo        =  $this->class_security->validate_var($datas,'u_propietario');
    $telefono1   =  $this->class_security->validate_var($datas,'u_telefono1');
    $telefono2    =  $this->class_security->validate_var($datas,'u_telefono2');
    $estado      =  $this->class_security->validate_var($datas,'u_estado');
    $tipo_contacto      =  $this->class_security->validate_var($datas,'u_tipo_contacto');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="af_data_id" value="<?=$id?>">

    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col-md-6">
                <label>Nombre</label>
                <input type="text" id="af_nombre" name="nombre" value="<?=$nombre?>" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Tipo</label>
                <select class="form-control text-center imput_reset" required name="tipo" id="af_tipo" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php foreach($this->class_data->tipo_propietario As $t_k => $t_v) {
                            if($tipo == $t_k){
                                echo "<option value='{$t_k}' selected>{$t_v}</option>";
                            }else{
                                echo "<option value='{$t_k}'>{$t_v}</option>";
                            }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label>Contacto</label>
                <select class="form-control text-center imput_reset" required name="tipo_contacto" id="tipo_contacto" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php foreach($this->class_data->tipo_contacto As $t_k => $t_v) {
                            if($tipo_contacto == $t_k){
                                echo "<option value='{$t_k}' selected>{$t_v}</option>";
                            }else{
                                echo "<option value='{$t_k}'>{$t_v}</option>";
                            }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">

            <div class="form-group col-md-4">
                <label>Telefono 1</label>
                <input type="text" id="af_telefono1" name="telefono1" value="<?=$telefono1?>" placeholder="telefono1" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Telefono 2</label>
                <input type="text" id="af_telefono2" name="telefono2" value="<?=$telefono2?>" placeholder="telefono2" autofocus  class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="estado" id="af_estado" autocomplete="off">
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

        </div>
    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Condomino</button>
    </div>


</form>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-700');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_aflial_save');
                return false;
            }
        })

    })
</script>