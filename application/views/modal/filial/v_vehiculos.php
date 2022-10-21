<?php
defined('BASEPATH') or exit('No direct script access allowed');


    $tipo      = $this->class_security->validate_var($data,'pv_tipo');
    $marca     = $this->class_security->validate_var($data,'pv_placa');
    $placa     = $this->class_security->validate_var($data,'pv_color');
    $color     = $this->class_security->validate_var($data,'pv_marca');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" value="<?=$id?>">

    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col-md-5">
                <label>Tipo Vehiculo</label>
                <select class="form-control text-center imput_reset" required name="tipo_vehiculo" id="v_tipo_vehiculo" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach ($this->class_data->autos as $t_id => $t_val){
                        if($tipo == $t_id){
                            echo "<option value='{$t_id}' selected>{$t_val}</option>";
                        }else{
                            echo "<option value='{$t_id}'>{$t_val}</option>";
                        }
                    }
                    ?>

                </select>
            </div>

            <div class="form-group col-md-6">
                <label>Marca</label>
                <input type="text" id="v_marca" name="marca" value="<?=$marca?>"  placeholder="Marca" required autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-1">
                <label class="d-block">Borrar</label>
                <button type="button" class="btn btn-dark" onclick="$(this).clear_form()"><i class="fa fa-eraser"></i></button>
            </div>
        </div>
       <div class="row mb-3">

            <div class="form-group col-md-6">
                <label>Placa</label>
                <input type="text" id="v_placa" name="placa" value="<?=$placa?>"  placeholder="Placa" required autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-6">
                <label>Color</label>
                <input type="text" id="v_color" name="color" value="<?=$color?>"  placeholder="color" required autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

        </div>

       <div class="row mb-3">

            <div class="form-group col-md-12">
                <label>Imagen Vehiculo</label>
                    <input type="file" name="imagen"  accept=".gif,.jpg,.jpeg,.png" class="form-control imput_reset" id="customFile" >
            </div>
       </div>

         <div class="row mb-3">

            <div class="form-group col-md-12">
                <div class="progress" style="height: 26px;">
                    <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="table-responsive">
                <table id="modal_tabla" class="table table-padded text-center">
                    <thead>
                    <tr>
                        <th>
                            Imagen
                        </th>
                        <th>
                            Tipo Vehiculo
                        </th>
                        <th>
                            Marca
                        </th>
                        <th>
                            Placas
                        </th>
                        <th>
                            Color
                        </th>
                        <th>
                            acción
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($datas)){
                        foreach ($datas As $dt){
                            $id_data = encriptar($dt['pv_id']);
                            $tipo = $this->class_security->array_data($dt['pv_tipo'],$this->class_data->autos);
                            $imagen = $this->class_security->img_avatar($dt['pv_imagen'],2);
                            echo "<tr>
                                          <td>$imagen</td>
                                          <td>$tipo</td>
                                          <td>$dt[pv_marca]</td>
                                          <td>$dt[pv_placa]</td>
                                          <td>$dt[pv_color]</td>
                                          <td>
                                            <div class='btn-group'>
                                              <button type='button'  onclick='$(this).forms_modal({\"page\" : \"f_vehiculos\",\"data1\" : \"{$id_data}\",\"title\" : \"Vehiculos\"})'  class='btn btn-primary'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fas fa-pencil-alt text-white'></i></button>
                                              <button type='button' class='btn btn-danger' onclick='$(this).dell_data(\"{$id_data}\",\"url_dell_vehiculo\",false,function(){
                                                     $(this).forms_modal({\"page\" : \"f_vehiculos\",\"data1\" : \"{$id}\",\"title\" : \"Vehiculos\"})
                                              })'><i class='fa fa-times'></i></button>
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
        $(this).clear_modal_view('modal-800');
        $(this).upload_file();
        $(this).datatable_func('#modal_tabla');


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
                    $(this).simple_call('frm_data','url_save_vehiculo',false,() => {
                    $(this).forms_modal({"page" : "f_vehiculos","title" : "Vehiculos"})
                });
                return false;
            }
        })

    })
</script>
