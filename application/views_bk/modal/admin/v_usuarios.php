<?php
defined('BASEPATH') or exit('No direct script access allowed');

    $nombre         = $this->class_security->validate_var($datas,'u_nombre');
    $usuario        = $this->class_security->validate_var($datas,'u_username');
    $perfil         = $this->class_security->validate_var($datas,'u_perfil');
    $estado         = $this->class_security->validate_var($datas,'u_estado');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col-md-7">
                <label>Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?=$nombre?>" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Usuario</label>
                <input type="usuario" id="usuario" name="usuario" value="<?=$usuario?>"  placeholder="Usuario" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-1">
                <label class="d-block">Borrar</label>
                <button type="button" class="btn btn-dark" onclick="$(this).clear_form();$('#password').attr('required',true);"><i class="fa fa-eraser"></i></button>
            </div>
        </div>

        <div class="row mb-3">

            <div class="form-group col-md-4">
                <label>Contraseña</label>
                <input type="text" id="password" name="password" placeholder="Contraseña" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Tipo Usuario</label>
                <select class="form-control text-center imput_reset" required name="perfil" id="perfil" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($this->class_data->perfiles As $p_id => $p_val){
                        if(in_array($p_id,[1,3,4,5])){
                            if($perfil == $p_id){
                                echo "<option value='$p_id' selected>$p_val</option>";
                            }else{
                                echo "<option value='$p_id'>$p_val</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="estado" id="estado" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($this->class_data->estado_simple As $e_id => $e_vl)
                    {
                        if($e_id == $estado){
                            echo "<option value='$e_id' selected>$e_vl[title]</option>";
                        }else{
                            echo "<option value='$e_id'>$e_vl[title]</option>";
                        }

                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-800');

        <?php
        if(strlen($id) >= 5){
            echo " $('#password').attr('required',false);";
        }
        ?>


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

