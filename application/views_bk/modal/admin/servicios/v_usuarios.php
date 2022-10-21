<?php
defined('BASEPATH') or exit('No direct script access allowed');

$dt = isset($data[0]) ? $data[0] : [];
$nombre          = $this->class_security->validate_var($dt,'u_nombre');
$usuario         = $this->class_security->validate_var($dt,'u_username');
$correo          = $this->class_security->validate_var($dt,'u_correo1');
$telefono        = $this->class_security->validate_var($dt,'u_telefono1');
$estado          = $this->class_security->validate_var($dt,'u_estado');
$tipo_documento  = $this->class_security->validate_var($dt,'sp_tipo_documento');
$documento       = $this->class_security->validate_var($dt,'sp_documento');
?>
<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" class="imput_reset" value="<?=$id?>">
    <input type="hidden" name="perfil"  value="6">

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
                <input type="text" name="password"  id="password" <?=(strlen($nombre) >= 0) ? '' : 'required'?> autofocus class="form-control  imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Telefono</label>
                <input type="text" name="telefono1"  id="telefono1" value="<?=$telefono?>" required autofocus  class="form-control  imput_reset" autocomplete="off">
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

        <div class="row mb-3">

            <div class="form-group col-md-4">
                <label>Correo</label>
                <input type="email"  id="correo" name="correo1" value="<?=$correo?>" placeholder="Correo electronico" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Categoria</label>
                <select type="text" name="tipo"  id="tipo" required autofocus style="width:100%" class="form-control  imput_reset" autocomplete="off">
                    <option value="">[ SELECCIONAR ]</option>
                    <?php
                    foreach($this->class_data->tipo_documentos As $t_id => $t_val):
                        if($tipo_documento == $t_id):
                            echo "<option value='$t_id' selected>$t_val</option>";
                        else:
                            echo "<option value='$t_id'>$t_val</option>";
                        endif;
                    endforeach;
                    ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Numero Documento</label>
                <input type="text" name="documento"  id="documento" value="<?=$documento?>" required autofocus style="width:100%" class="form-control  imput_reset" autocomplete="off">
            </div>

        </div>


        <div class="row mt-4">
            <div class="table-responsive">
                <table id="modal_table" class="table table-striped table-padded text-center">
                    <thead>
                    <tr>
                        <th> Nombre</th>
                        <th> Correo</th>
                        <th> Telefono</th>
                        <th> Estado</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($datas)){
                        foreach ($datas As $dt){
                            $id = encriptar($dt->u_id);
                            $estado     = $this->class_data->estado_simple[$dt->u_estado];
                            echo "<tr>
                                          <td>$dt->u_nombre</td>
                                          <td>$dt->u_correo1</td>
                                          <td>$dt->u_telefono1</td>
                                          <td><button class='{$estado['class']}'>{$estado['title']}</button></td>
                                          <td>
                                            <div class='btn-group'>
                                          <button type='button' onclick=' $(this).forms_modal({\"page\" : \"servicios_usuarios\",\"data1\" : \"{$id}\",\"title\" : \"Usuario de Servicios\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
                                          <button type='button' class='btn btn-danger' onclick='$(this).dell_data(\"{$id}\",\"url_usuarios_delete\",false,function(){
                                                 $(this).forms_modal({\"page\" : \"servicios_usuarios\",\"title\" : \"Usuario de Servicios\"})
                                          })'><i class='fas fa-times'></i></button>
                                          </div>
                                          </td>
                                       </tr>";
                        }
                    }
                    ?>
                    </tbody>
                </table>
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
        $(this).datatable_func("#modal_table");

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {

                $(this).simple_call('frm_data','url_usuarios_save',false,() => {
                    $(this).forms_modal({'page' : 'servicios_usuarios','title' : 'Usuario de Servicios'})
                });
                return false;
            }
        })

    })
</script>