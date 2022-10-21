<?php
defined('BASEPATH') or exit('No direct script access allowed');

//print_r($datas);
$pnombre             = $this->class_security->validate_var($datas,'p_nombre');
$valor             = $this->class_security->validate_var($datas,'r_valor');
$nombre             = $this->class_security->validate_var($datas,'r_nombre');
$telefono           = $this->class_security->validate_var($datas,'r_telefono');
$correo             = $this->class_security->validate_var($datas,'r_correo');
$asistencia         = $this->class_security->validate_var($datas,'r_asistencia');
$f_inicio           = $this->class_security->validate_var($datas,'r_fecha_inicio');
$f_salida           = $this->class_security->validate_var($datas,'r_fecha_salida');
$estado             = $this->class_security->validate_var($datas,'r_estado');
$comprobante        = $this->class_security->validate_var($datas,'r_comprobante');
$observacion        = $this->class_security->validate_var($datas,'r_observacion');
$atcreate           = $this->class_security->validate_var($datas,'r_atcreate');

$view = 'none';



?>
<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <input type="hidden" id="url_acción">

    <div class="modal-body text-center">



        <div class="row mb-3">
            <div class="form-group col-md-8">
                <label for="">Propiedad</label>
                <input class="form-control text-center"  value="<?=$pnombre?>"  readonly placeholder="Nombre de Contacto..." type="text" >
            </div>


            <div class="form-group col-md-4">
                <label for="">Estado</label>
                <input class="form-control text-center"  value="<?=$this->class_data->restado_reserva[$estado]['title']?>"  readonly placeholder="Nombre de Contacto..." type="text" >
            </div>
        </div>



        <div class="row mb-3">

            <div class="form-group col-md-4">
                <label for="">Nombre Alternativo</label>
                <input class="form-control text-center"  value="<?=$nombre?>"  readonly placeholder="Nombre de Contacto..." type="text" >
            </div>

            <div class="form-group col-md-3">
                <label for="">Telefono Alternativo</label>
                <input class="form-control text-center" value="<?=$telefono?>"  readonly placeholder="Telefono de Contacto..." type="text" >
            </div>

            <div class="form-group col-md-3">
                <label for="">Correo Alternativo</label>
                <input class="form-control text-center" value="<?=$correo?>"  readonly placeholder="Correo de Contacto..." type="text" >
            </div>


            <div class="form-group col-md-2">
                <label for="">Visitantes</label>
                <input type="text" class="form-control text-center " value="<?=$asistencia?>"  readonly placeholder="Cantidad">
            </div>
        </div>

        <div class="row mb-3">

            <div class="form-group col-md-3">
                <label for="">Valor Cancelar</label>
                <input class="form-control text-center"  value="₡ <?=$valor?>"  readonly placeholder="Nombre de Contacto..." type="text" >
            </div>

            <div class='form-group col-md-9'>
                <label>Comprobante</label>
            <?php
                if($estado == 3):
            ?>
                <input type='file' id='comprobante' name='comprobante' required accept=".gif,.jpg,.jpeg,.png,.doc,.docx, .pdf"   autofocus  class='form-control text-center imput_reset' autocomplete='off'>

            <?php
                else:
                   echo $this->class_security->url_documento($comprobante,1,'w-100');
                endif;
            ?>
            </div>
        </div>

        <?php
        if($estado == 3):
        ?>
        <div class="row mb-3">
            <div class='form-group col-md-12'>
                <div class='progress' style='height: 26px;'>
                    <div class='progress-bar  progress-bar-striped  progress-bar-animated' role='progressbar' id='progressbar_deuda_total' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100'>Barra de progreso</div>
                </div>
            </div>
        </div>
        <?php
        endif;
        ?>

        <div class="row mb-3">
            <div class="form-group col-md-12">
                <label for="">Observacion</label>
                <textarea type="text" class="form-control"  readonly ><?=$observacion?></textarea
            </div>
        </div>

        <div class="row  mt-3">
            <div class="table-responsive">
                <table class="table table-padded text-center">
                    <thead>
                    <tr>
                        <th>
                            Fecha
                        </th>
                        <th>
                            Hora Inicial
                        </th>
                        <th>
                            Hora Salida
                        </th>
                        <th>
                            Estado
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($dias)){
                        foreach ($dias As $dt){
                            $estadodd = $this->class_data->restado_reserva_dias[$dt->rd_estado];
                            $fecha = $this->class_security->substr($dt->rd_fecha1,0,10);
                            $inicio = $this->class_security->substr($dt->rd_fecha1,11,19);
                            $salida = $this->class_security->substr($dt->rd_fecha2,11,19);
                            //desglose

                            echo "<tr>
                                          <td>$fecha</td>
                                          <td>$inicio</td>
                                          <td>$salida</td>
                                          <td><button class='{$estadodd['class']}'>{$estadodd['title']}</button></td>
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
        <?=($estado == 3) ?  '<button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>' : '';?>
    </div>


</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-900','.in_modal_secundario');

        // $(this).fecha_func('#fecha');
        // $(this).dinero_func('#monto');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_comprobante_reserva');
                return false;
            }
        })

    })
</script>