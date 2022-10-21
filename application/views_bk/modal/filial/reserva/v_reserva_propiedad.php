<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data" autocomplete="off">
    <input type="hidden" name="inicial" id="inicial"  value="<?=$data1?>">
    <input type="hidden" name="salida" id="salida"  value="<?=$data2?>">

    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col-md-7">
                <label for="">Nombre propiedad</label>
                <select name="propiedad"  class="form-control text-center" onchange="$(this).seleccionar_propiedad();" id="propiedad">
                    <option value='' selected disabled hidden>[ SELECCIONAR ]</option>
                    <?php
                    foreach($datas As $dd){
                        $p_id = encriptar($dd['p_id']);
                        $p_nombre = $dd['p_nombre'];
                        echo "<option value='$p_id'>$p_nombre</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="">Cantidad Max</label>
                <input class="form-control text-center" id="m_cantidad" readonly placeholder="Cant." type="text">
            </div>

            <div class="form-group col-md-3">
                <label for="">Valor reserva</label>
                <input class="form-control text-center" readonly id="m_valor" placeholder="Precio" type="text">
            </div>

        </div>

        <div class="row mb-3">

            <div class="form-group col-md-4">
                <label for="">Nombre Alternativo</label>
                <input class="form-control imput_reset" name="nombre" placeholder="Nombre de Contacto..." type="text" value="">
            </div>

            <div class="form-group col-md-3">
                <label for="">Telefono Alternativo</label>
                <input class="form-control imput_reset" name="telefono" placeholder="Telefono de Contacto..." type="text" value="">
            </div>

            <div class="form-group col-md-3">
                <label for="">Correo Alternativo</label>
                <input class="form-control imput_reset" name="correo" placeholder="Correo de Contacto..." type="text" value="">
            </div>


            <div class="form-group col-md-2">
                <label for="">Visitantes</label>
                <input type="text" class="form-control imput_reset text-center numeros" readonly value="0" required placeholder="Cantidad" id="cantidad" name="asistencia">
            </div>
        </div>

        <div class="row  mb-3 text-center">

            <div class="form-group col-md-3">
                <label for="">Precio</label>
                <input type="text" class="form-control imput_reset text-center numeros" readonly required placeholder="0" id="precio" name="precio">
            </div>

            <div class="form-group col-md-9">
                <label for="">Comprobante</label>
                <input type="file" name="comprobante" required accept=".gif,.jpg,.jpeg,.png,.doc,.docx, .pdf" class="form-control imput_reset" id="customFile" >
            </div>

        </div>

        <div class="row  mb-3 text-center">
            <div class="form-group col-md-12">
                <div class="progress" style="height: 26px;">
                    <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                </div>
            </div>

        </div>


        <div class="row">
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
                            acción
                        </th>
                    </tr>
                    </thead>
                    <tbody id="tabla_dias">
                    <?php
                    foreach($dias As $dd){
                        $hora_limpia = $this->class_security->letras_numeros($dd);
                        echo "
                        <tr id='id_{$hora_limpia}'>
                                <td class='reset_table_hora' id='fecha_{$hora_limpia}'>{$dd}</td>
                                <td>
                                    <div class='input-group'>
                                        <div class='input-group-prepend'>
                                            <div class='input-group-text' onclick=\"$(this).clonar_hora('hora_{$hora_limpia}','hora_entrada');\">
                                                <i class='fa fa-calendar text-primary'></i>
                                            </div>
                                        </div>
                                        <input type='hidden' name='fecha[{$hora_limpia}][fecha]' value='{$dd}'>
                                        <input class='form-control text-center pickadatetime hora_entrada' name='fecha[{$hora_limpia}][inicial]' required id='hora_{$hora_limpia}' placeholder='Hora Inicial' type='text'>
                                    </div>
                                </td>
                                <td>
                                    <div class='input-group'>
                                        <div class='input-group-prepend'>
                                            <div class='input-group-text' onclick=\"$(this).clonar_hora('salida_{$hora_limpia}','hora_salida');\">
                                                <i class='fa fa-calendar text-primary'></i>
                                            </div>
                                        </div>
                                        <input class='form-control text-center pickadatetime hora_salida'  name='fecha[{$hora_limpia}][final]' required id='salida_{$hora_limpia}' placeholder='Hora Salida' type='text'>
                                    </div>
                                </td>
                                <td>
                                    <div class='btn-group'>
                                        <button class='btn btn-danger' onclick=\"$(this).eliminar_hora('{$hora_limpia}')\"><i class='fa fa-times'></i></button>
                                    </div>
                                </td>
                            </tr>
                        ";
                    }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Reserva</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-900');
        $(this).numeros_func('.numeros');
        $(this).datehour_func('.pickadatetime');

        let cantidad = $("#cantidad");
         //
         cantidad.on('keyup',function(){
             let cantidad_max = $("#m_cantidad").val();
             if(cantidad.val() > parseInt(cantidad_max)){
                 $(this).mensaje_alerta(1, "Lo siento superar la cuota maxima de invitados");
                 cantidad.val('').change();
                 return false;
             }
         })

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save',false,function(result){

                    $('.reset_table_hora').css('background-color','white');
                    if (result.response.success == 1) {
                        window.location.reload();
                    }else if(result.response.success == 2){
                        $(this).mensaje_alerta(1, result.response.msg);
                    }
                    else {
                        $(this).mensaje_alerta(1, result.response.msg);
                        let data = result.response.data;
                        data.forEach(function(dd){
                            let change = dd.replaceAll('-','');
                            let tablafecha = $(`#fecha_${change}`);
                            if(tablafecha.length >= 1){
                                tablafecha.css('background-color','red');
                            }else{
                                tablafecha.css('background-color','white');

                            }
                        })
                    }
                },true,true);
                return false;
            }
        })

    })
</script>
