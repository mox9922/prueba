<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="v_data_id" class="imput_reset">
    <input type="hidden" name="propietario" value="<?=$id?>">

    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col-md-5">
                <label>Tipo del Vehículo</label>
                <select class="form-control text-center imput_reset" required name="tipo_vehiculo" id="v_tipo_vehiculo" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach ($this->class_data->autos as $t_id => $t_val){
                        echo "<option value='{$t_id}'>{$t_val}</option>";
                    }
                    ?>

                </select>
            </div>

            <div class="form-group col-md-6">
                <label>Marca</label>
                <input type="text" id="v_marca" name="marca" placeholder="Marca" required autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-1">
                <label class="d-block">Borrar</label>
                <button type="button" class="btn btn-dark" onclick="$(this).clear_form()"><i class="fa fa-eraser"></i></button>
            </div>

        </div>

        <div class="row mb-3">
            <div class="form-group col-md-6">
                <label>Placa</label>
                <input type="text" id="v_placa" name="placa" placeholder="Placa" required autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-6">
                <label>Color</label>
                <input type="text" id="v_color" name="color" placeholder="color" required autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>
        </div>

        </div>

        <div class="row">
            <div class="table-responsive">
                <table id="modal_tabla" class="table table-padded text-center">
                    <thead>
                    <tr>
                        <th>
                            Tipo Vehiculo
                        </th>
                        <th>
                            Placas
                        </th>
                        <th>
                            Color
                        </th>
                        <th>
                            Marca
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
                            echo "<tr>
                                          <td>$tipo</td>
                                          <td>$dt[pv_marca]</td>
                                          <td>$dt[pv_placa]</td>
                                          <td>$dt[pv_color]</td>
                                          <td>
                                            <div class='btn-group'>
                                              <button type='button' onclick='$(this).edit_vehiculo(\"{$id_data}\");' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
                                              <button type='button' class='btn btn-danger' onclick='$(this).dell_data(\"{$id_data}\",\"url_dell_vehiculo\",false,function(){
                                                     $(this).forms_modal({\"page\" : \"propietario_vehiculos\",\"data1\" : \"{$id}\",\"title\" : \"Vehiculos\"})
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
        $(this).clear_modal_view('modal-900');
        $(this).datatable_func('#modal_tabla');



        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_vehiculo',false,() => {
                    $(this).forms_modal({"page" : "propietario_vehiculos","data1" : "<?=$id?>","title" : "Vehiculos"})
                });
                return false;
            }
        })

    })
</script>
