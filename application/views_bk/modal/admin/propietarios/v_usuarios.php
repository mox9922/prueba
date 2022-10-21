<?php
defined('BASEPATH') or exit('No direct script access allowed');

    $nombre         = $this->class_security->validate_var($datas,'u_nombre');
    $usuario        = $this->class_security->validate_var($datas,'u_username');
    $telefono1      = $this->class_security->validate_var($datas,'u_telefono1');
    $telefono2      = $this->class_security->validate_var($datas,'u_telefono2');
    $correo1        = $this->class_security->validate_var($datas,'u_correo1');
    $correo2        = $this->class_security->validate_var($datas,'u_correo2');
    $apartamento    = $this->class_security->validate_var($datas,'u_apartamento');
    $estado         = $this->class_security->validate_var($datas,'u_estado');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <input type="hidden" name="perfil" id="perfil" value="2">

    <div class="modal-body">



        <div class="row mb-3">
            <div class="form-group col-md-6">
                <label>Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?=$nombre?>" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-5">
                <label>Usuario</label>
                <input type="usuario" id="usuario" name="usuario" value="<?=$usuario?>"  placeholder="Usuario" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-1">
                <label class="d-block">Borrar</label>
                <button type="button" class="btn btn-dark" onclick="$(this).clear_form();$('#password').attr('required',true);"><i class="fa fa-eraser"></i></button>
            </div>

        </div>

        <div class="row mb-3">

                <div class="form-group col-md-3">
                    <label>Telefono 1</label>
                    <input type="text" id="telefono1" name="telefono1" value="<?=$telefono1?>"  placeholder="telefono1" autofocus  class="form-control imput_reset" autocomplete="off">
                </div>


                <div class="form-group col-md-3">
                    <label>telefono2</label>
                    <input type="text" id="telefono2" name="telefono2" value="<?=$telefono2?>"  placeholder="telefono2" autofocus  class="form-control imput_reset" autocomplete="off">
                </div>


                <div class="form-group col-md-3">
                    <label>Correo 1</label>
                    <input type="text" id="correo1" name="correo1" value="<?=$correo1?>"  placeholder="Correo 1" autofocus  class="form-control imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <label>Correo 2</label>
                    <input type="text" id="correo2" name="correo2" value="<?=$correo2?>"  placeholder="Correo 2" autofocus  class="form-control imput_reset" autocomplete="off">
                </div>

        </div>

        <div class="row mb-3">

                <div class="form-group col-md-4">
                    <label>Contraseña</label>
                    <input type="text" id="password" name="password" required placeholder="Contraseña" autofocus  class="form-control imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-4">
                    <label>Filial</label>
                    <select class="form-control text-center select imput_reset" style="width:100%;" required name="apartamento" id="apartamento" autocomplete="off">
                        <option value=""> [ SELECCIONAR ] </option>
                        <?php
                        foreach($apartamentos As $apt){
                            if($apt->t_id == $apartamento){
                                echo "<option value='{$apt->t_id}' selected>{$apt->t_torre} - {$apt->t_apartamento}</option>";
                            }else{
                                echo "<option value='{$apt->t_id}'>{$apt->t_torre} - {$apt->t_apartamento}</option>";
                            }

                        }
                        ?>
                    </select>
                    <div id="myModal"></div>
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
                    <div id="myModal"></div>
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
        $(this).clear_modal_view('modal-900');
        $(this).select2_func('.select');

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
            }
        })

    })
</script>

