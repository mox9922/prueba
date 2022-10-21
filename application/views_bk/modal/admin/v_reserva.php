<?php
defined('BASEPATH') or exit('No direct script access allowed');


?>
<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <input type="hidden" id="url_acción">

    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col-md-4">
                <label>Nombre</label>
                <input type="text" id="nombre" value="<?=$reserva->r_nombre?>" readonly class="form-control  text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Telefono</label>
                <input type="text" id="telefono" value="<?=$reserva->r_telefono?>" readonly class="form-control  text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Correo</label>
                <input type="text" id="correo" value="<?=$reserva->r_correo?>"  readonly class="form-control  text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-2">
                <label>asistentes</label>
                <input type="text" id="asistencia" value="<?=$reserva->r_asistencia?>" readonly class="form-control  text-center imput_reset" autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">

        <div class="form-group col-md-2">
                <label>Fecha Entrada</label>
                <input type="text" id="f_entrada" value="<?=$reserva->r_fecha_inicio?>" readonly class="form-control  text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-2">
                <label>Fecha Salida</label>
                <input type="text" id="f_salida" value="<?=$reserva->r_fecha_salida?>" readonly class="form-control  text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Fecha Creacion</label>
                <input type="text" id="f_creacion" value="<?=$reserva->r_atcreate?>" disabled class="form-control  text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-2">
                <label>Valor</label>
                    <input type="text" id="valor" name="valor" value="<?=$reserva->r_valor?>"  class="form-control  text-center dinero imput_reset" autocomplete="off">
            </div>


            <div class="form-group col-md-3">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="estado" id="estado" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($this->class_data->restado_reserva As $es_id => $es_val){
                        if($reserva->r_estado == $es_id){
                            echo "<option value='{$es_id}' selected>{$es_val['title']}</option>";
                        }else{
                            echo "<option value='{$es_id}'>{$es_val['title']}</option>";
                        }

                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">

        <div class="form-group col-md-12">
                <label>Observación</label>
                <textarea name="observacion" id="observacion" rows="6"  class="form-control imput_reset" autocomplete="off"><?=$reserva->r_observacion?></textarea>
            </div>
        </div>

        <div class="row mt-4">
            <div class="table-responsive">
                <table id="modal_table" class="table table-striped table-padded text-center">
                    <thead>
                    <tr>
                        <th> Fecha</th>
                        <th> Hora Inicio</th>
                        <th> Hora Finalizacion</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($reserva_dias)){
                        foreach ($reserva_dias As $dt){
                            $id         = encriptar($dt['rd_id']);
                            $fecha      = substr($dt['rd_fecha1'],0,10);
                            $ingreso    = substr($dt['rd_fecha1'],12,19);
                            $salida     = substr($dt['rd_fecha2'],12,19);
                            $estado_act = $this->class_security->select_pago_valor($id,$this->class_data->restado_reserva,$dt['rd_estado'],'actualizar_estado_reserva');

                            echo "<tr>
                                          <td>$fecha</td>
                                          <td>$ingreso</td>
                                          <td>$salida</td>
                                          <td>$estado_act</td>
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

        // $(this).fecha_func('#fecha');
        $(this).dinero_func('.dinero');


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