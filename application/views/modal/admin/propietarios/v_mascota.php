<?php
defined('BASEPATH') or exit('No direct script access allowed');


$idm            =  $this->class_security->validate_var($mascota,'pm_id');
$tipo           =  $this->class_security->validate_var($mascota,'pm_tipo');
$nombre         =  $this->class_security->validate_var($mascota,'pm_nombre');
$color          =  $this->class_security->validate_var($mascota,'pm_color');
$raza           =  $this->class_security->validate_var($mascota,'pm_raza');
$observacion    =  $this->class_security->validate_var($mascota,'pm_observacion');

?>
<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="m_data_id" class="imput_reset" value="<?=encriptar($idm)?>">
    <input type="hidden" name="apartamento" id="m_apartamento"  value="<?=$id?>">

    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col-md-11">
                <label>Tipo Mascota</label>
                <select class="form-control text-center imput_reset" required name="tipo" id="m_tipo" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach ($this->class_data->mascota as $t_id => $t_val){
                        if($tipo == $t_id){
                            echo "<option value='{$t_id}' selected>{$t_val}</option>";
                        }else{
                            echo "<option value='{$t_id}' >{$t_val}</option>";
                        }

                    }
                    ?>

                </select>
            </div>

            <div class="form-group col-md-1">
                <label class="d-block">Borrar</label>
                <button type="button" class="btn btn-dark" onclick="$(this).clear_form()"><i class="fa fa-eraser"></i></button>
            </div>

        </div>

        <div class="row mb-3">

            <div class="form-group col-md-4">
                <label>Nombre</label>
                <input type="text" id="m_nombre" name="nombre" value="<?=$nombre?>" required placeholder="nombre" autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Color</label>
                <input type="text" id="m_color" name="color" value="<?=$color?>" required placeholder="color" autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Raza</label>
                <input type="text" id="m_raza" name="raza" value="<?=$raza?>" required placeholder="Raza" autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

        </div>


        <div class="row mb-3">
            <div class="form-group col-md-12">
                <label>Imagen de Mascota</label>
                <input type="file" name="imagen"   accept=".gif,.jpg,.jpeg,.png" class="form-control imput_reset" id="customFile" >
            </div>

        </div>

        <div class="row mb-3">
            <div class="form-group col-md-12">
                <div class="progress" style="height: 26px;">
                    <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                </div>
            </div>

        </div>

        <div class="row mb-3">
            <div class="form-group col-md-12">
                <label>Observación</label>
                <textarea  id="m_descripcion" name="observacion" rows="7" placeholder="observacion" autofocus  class="form-control imput_reset" autocomplete="off"><?=$observacion?></textarea>
            </div>
        </div>
        </div>

        <div class="row">
            <div class="table-responsive">
                <table class="table table-padded text-center">
                    <thead>
                    <tr>
                        <th>
                            Imagen
                        </th>
                        <th>
                            Nombre
                        </th>
                        <th>
                            Tipo Mascota
                        </th>
                        <th>
                            Color
                        </th>
                        <th>
                            Raza
                        </th>
                        <th>
                            acción
                        </th>
                    </tr>
                    </thead>
                    <tbody id="tabla_mascotas">
                    <?php
                    if(isset($datas)){
                        foreach ($datas As $dt){
                            $id_data = encriptar($dt['pm_id']);
                            $id_apt = encriptar($dt['pm_apartamento']);
                            $tipo = $this->class_security->array_data($dt['pm_tipo'],$this->class_data->mascota);
                            $imagen = $this->class_security->img_avatar($dt['pm_imagen'],2);
                            echo "<tr>
                                          <td>$imagen</td>
                                          <td>$dt[pm_nombre]</td>
                                          <td>$tipo</td>
                                          <td>$dt[pm_color]</td>
                                          <td>$dt[pm_raza]</td>
                                          <td>
                                            <div class='btn-group'>
                                              <button type='button' onclick='$(this).forms_modal({\"page\" : \"propietario_mascotas\",\"data1\" : \"{$id_apt}\",\"data2\" : \"{$id_data}\",\"title\" : \"Mascotas\"});' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
                                              <button type='button' class='btn btn-danger' onclick='$(this).dell_data(\"{$id_data}\",\"url_dell_mascotas\",false,function(){
                                                     $(this).forms_modal({\"page\" : \"propietario_mascotas\",\"data1\" : \"{$id}\",\"title\" : \"Mascotas\"})
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
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Mascota</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-900');
        $(this).upload_file();
            $('.single_image').fancybox({
                'zoomSpeedIn': 300,
                'zoomSpeedOut': 300,
                'overlayShow': false
            });

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_mascotas',false,() => {
                    $(this).forms_modal({"page" : "propietario_mascotas","data1" : "<?=$id?>","title" : "Mascotas"})
                });
                return false;
            }
        })

    })
</script>
