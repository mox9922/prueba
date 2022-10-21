<?php
defined('BASEPATH') or exit('No direct script access allowed');

$did                =  $this->class_security->validate_var($invitado,'pv_id');
$nombre             =  $this->class_security->validate_var($invitado,'pv_nombre');
$cedula             =  $this->class_security->validate_var($invitado,'pv_cedula');
$telefono           =  $this->class_security->validate_var($invitado,'pv_telefono');
$tipo_vehiculo      =  $this->class_security->validate_var($invitado,'pv_tipo_vehiculo');
$placa              =  $this->class_security->validate_var($invitado,'pv_placa');
$tipo_invitado      =  $this->class_security->validate_var($invitado,'pv_tipo_invitado');
$fecha_ingreso      =  $this->class_security->validate_var($invitado,'pv_fecha_ingreso');
$estado             =  $this->class_security->validate_var($invitado,'pv_estado');

$vehiculo_data      = ((isset($tipo_vehiculo)) AND (strlen($tipo_vehiculo) >= 1)) ? $this->class_security->array_data($tipo_vehiculo,$this->class_data->autos,'') : '';
?>


<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="mi_data_id" class="imput_reset" value="<?=encriptar($did)?>">
    <input type="hidden" name="apartamento" id="mi_apartamento" value="<?=$id?>">


    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col-md-6">
                <label>Nombre</label>
                <input type="text" readonly value="<?=$nombre?>" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-5">
                <label>Cedula</label>
                <input type="text" readonly value="<?=$cedula?>" placeholder="Cedula" autofocus  class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-1">
                <label class="d-block">Borrar</label>
                <button type="button" class="btn btn-dark" onclick="$(this).clear_form()"><i class="fa fa-eraser"></i></button>
            </div>

        </div>

        <div class="row mb-3">

            <div class="form-group col-md-4">
                <label>Tipo Vehiculo</label>
                    <input type="text" readonly value="<?=$vehiculo_data?>" placeholder="Tipo Vehiculo" autofocus  class="form-control imput_reset" autocomplete="off">
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Placa</label>
                <input type="text" readonly value="<?=$placa?>" placeholder="Placa" autofocus  class="form-control imput_reset" autocomplete="off">
            </div>


            <div class="form-group col-md-4">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="estado" id="mi_estado" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach ($this->class_data->estado_simple as $e_id => $e_val){
                        if($estado == $e_id){
                            echo "<option value='{$e_id}' selected>{$e_val['title']}</option>";
                        }else{
                            echo "<option value='{$e_id}' >{$e_val['title']}</option>";
                        }

                    }
                    ?>
                </select>
            </div>

        </div>

        </div>

        <div class="row">
            <div class="table-responsive px-4">
                <table id="modal_table" class="table table-padded text-center">
                    <thead>
                    <tr>
                        <th>
                            Foto
                        </th>
                        <th>
                            Nombre
                        </th>
                        <th>
                            Cedula
                        </th>
                        <th>
                            Tipo Vehiculo
                        </th>
                        <th>
                            placa
                        </th>
                        <th>
                            Tipo invitado
                        </th>
                        <th>
                            Fecha Ingreso
                        </th>
                        <th>
                            Estado
                        </th>
                        <th>
                            acción
                        </th>
                    </tr>
                    </thead>
                    <tbody id="tabla_invitados">
                    <?php
                    if(isset($datas)){
                        foreach ($datas As $dt){
                            $id_data = encriptar($dt['pv_id']);
                            $tipo = $this->class_security->array_data($dt['pv_tipo_vehiculo'],$this->class_data->autos);
                            $imagen = $this->class_security->img_avatar($dt['pv_foto'],2);
                            $estador  = $this->class_security->array_data($dt['pv_estado'],$this->class_data->estado_simple);
                            $tipo = $this->class_security->array_data($dt['pv_tipo_invitado'],$this->class_data->tipo_invitado,[]);

                            echo "<tr>
                                          <td>$imagen</td>
                                          <td>$dt[pv_nombre]</td>
                                          <td>$dt[pv_cedula]</td>
                                          <td>$tipo</td>
                                          <td>$dt[pv_placa]</td>
                                          <td>$tipo</td>
                                          <td>$dt[pv_fecha_ingreso]</td>
                                          <td><button class='{$estador['class']}'>{$estador['title']}</button></td>
                                          <td>
                                            <div class='btn-group'>
                                              <button type='button' onclick='$(this).forms_modal({\"page\" : \"propietario_invitados\",\"data1\" : \"{$id}\",\"data2\" : \"{$id_data}\",\"title\" : \"Invitados\"});'  class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
                                              <button type='button' class='btn btn-danger' onclick='$(this).dell_data(\"{$id_data}\",\"url_dell_invitados\",false,function(){
                                                     $(this).forms_modal({\"page\" : \"propietario_invitados\",\"data1\" : \"{$id}\",\"title\" : \"Invitados\"})
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
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
    </div>


</form>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1100');
        $(this).datatable_func('#modal_table');

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
                $(this).simple_call('frm_data','url_save_invitados',false,() => {
                    $(this).forms_modal({"page" : "propietario_invitados","data1" : "<?=$id?>","title" : "Invitados"})
                });
                return false;
            }
        })

    })
</script>
