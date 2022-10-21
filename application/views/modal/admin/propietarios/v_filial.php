<?php
defined('BASEPATH') or exit('No direct script access allowed');

    $bloque  = $datas->t_torre;
    $filial  = $datas->t_apartamento;
    $cuota   = $datas->t_cuota;
    $tipo    = $datas->t_tipo;
    $estadof = $datas->t_estado_afilial;
    $estado  = $datas->t_estado;
    $correo  = $datas->t_correo;
    $fecha = date("Y-m");


?>
<div class="modal-body">

    <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

        <input type="hidden" name="send" value="data">
        <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">

        <div class="modal-body">

            <div class="row mb-3">
                <div class="form-group col-md-3">
                    <label>Bloque</label>
                    <input type="text" id="torre" name="torre" value="<?=$bloque?>" placeholder="Bloque" autofocus required class="form-control text-center imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <label>Filial</label>
                    <input type="text" id="apartamento" value="<?=$filial?>" name="apartamento" placeholder="Filial" autofocus required class="form-control text-center imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <label>Cuota Condominal</label>
                    <input type="text" id="cuota" name="cuota" value="<?=$cuota?>" placeholder="Cuota Condominal"  onkeyup="$(this).calcular_total();" autofocus required class="form-control text-center dinero imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <label>Pago Total</label>
                    <input type="text"  name="p_total" id="p_total" readonly autofocus  class="form-control text-center imput_reset" autocomplete="off">
                </div>
            </div>

            <div class="row mb-3">

                <div class="form-group col-md-3">
                    <label>Cuota Extraordinaria</label>
                    <input type="text"  name="cuota_ex" id="cuota_ex" placeholder="" autofocus onkeyup="$(this).calcular_extra();$(this).calcular_total();" class="form-control text-center dinero imput_reset" autocomplete="off">
                </div>


                <div class="form-group col-md-3">
                    <label># Pagos</label>
                    <input type="text"  name="n_pagos"  id="n_pagos" placeholder="" autofocus onkeyup="$(this).calcular_extra()" class="form-control text-center imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <label>Fecha Cobro</label>
                    <input type="text"  id="fecha_cobro" name="fecha_cobro" value="<?=$fecha?>"  autofocus  class="form-control  text-center " autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <label>Monto a pagar</label>
                    <input type="text"  name="m_pagar" id="m_pagar" readonly autofocus  class="form-control text-center dinero imput_reset" autocomplete="off">
                </div>

            </div>

            <div class="row mb-3">

                <div class="form-group col-md-3">
                    <label>Correo FIlial</label>
                    <input type="email"  name="correo" id="correo"  autofocus value="<?=$correo?>"  class="form-control imput_reset" autocomplete="off">
                </div>


                <div class="form-group col-md-3">
                    <label>Tipo</label>
                    <select class="form-control text-center  imput_reset" style="width:100%;" required name="tipo" id="tipo" autocomplete="off">
                        <option value=""> [ SELECCIONAR ] </option>
                        <?php
                        foreach($this->class_data->tipo_filial As $tf_id => $tf_vl){
                            if($tipo == $tf_id){
                                echo "<option value='{$tf_id}' selected>{$tf_vl}</option>";
                            }else{
                                echo "<option value='{$tf_id}'>{$tf_vl}</option>";
                            }

                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label>Estado Afilial</label>
                    <select class="form-control text-center  imput_reset" style="width:100%;" required name="tipo_estado" id="tipo_estado" autocomplete="off">
                        <option value=""> [ SELECCIONAR ] </option>
                        <?php
                        foreach($this->class_data->estado_filial As $tf_id => $tf_vl){
                            if($estadof == $tf_id){
                                echo "<option value='{$tf_id}' selected>{$tf_vl}</option>";
                            }else{
                                echo "<option value='{$tf_id}'>{$tf_vl}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label>Estado</label>
                    <select class="form-control text-center imput_reset" required name="estado" id="estado" autocomplete="off">
                        <option value=""> [ SELECCIONAR ] </option>
                        <?php
                        foreach($this->class_data->estado As $e_id => $e_vl){
                            if($estado == $e_id){
                                echo "<option value='{$e_id}' selected>{$e_vl['title']}</option>";
                            }else{
                                echo "<option value='{$e_id}'>{$e_vl['title']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

            </div>

            <div class="row mb-3">

                <div class="form-group col-md-12">
                    <label>Observacion Cuota Exraordinaria</label>
                    <textarea class="form-control imput_reset" rows="4" name="observacion" id="observacion" autocomplete="off"></textarea>
                </div>


            </div>

            <div class="table-responsive mx-4">
                <table id="modal_table" class="table table-padded text-center">
                    <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>
                            Valor
                        </th>
                        <th>
                            Pagos
                        </th>
                        <th>
                            Estado
                        </th>
                        <th>
                            Fecha aplicación
                        </th>
                        <th>
                            acción
                        </th>
                    </tr>
                    </thead>
                    <tbody id="tabla_modal">
                    <?php
                    if(isset($deudas)){
                        $i = 1;
                        foreach($deudas as $pg){
                            $id_extr = encriptar($pg['pg_id']);
                            $estado  = $this->class_security->array_data($pg['pg_estado'],$this->class_data->estado_pago);
                            echo "<tr> 
                                        <td>{$i}</td>
                                        <td class='bolder nowrap'><span class='text-success'>₡".$this->class_security->dinero($pg['pg_deuda'])."</span></td>
                                        <td class='bolder nowrap'><span class='text-success'>₡".$this->class_security->dinero($pg['agregado'])."</span></td>
                                         <td><button class='{$estado['class']}'>{$estado['title']}</button></td>
                                        <td>{$pg['pg_fecha']}</td>
                                        <td><button type='button' onclick='$(this).dell_data(\"{$id_extr}\",\"url_dell_filial_extra\",false,function(){
                                                $(this).forms_modal({\"page\" : \"propietario_filial\",\"data1\" : \"${id}\",\"title\" : \"Filial\"})
                                        })' class='btn btn-danger waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button></td>
                                     </tr>";

                            $i++;
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>


        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
            <button type="submit" class="btn btn-success waves-effect waves-light">Guardar </button>
        </div>


    </form>


</div>
<script>
    $(document).ready(function() {

        //remove size modal
        $(this).clear_modal_view('modal-1000');
        $(this).dinero_func('.dinero');
        $(this).fecha_func('#fecha_cobro','yyyy-mm-dd');
        $(this).numeros_func('#n_pagos');
        $(this).datatable_func("#modal_table");


        $.fn.calcular_total = function() {
            return this.each(function(){

                //cuota extraordinaria calculo
                let cuota = $("#cuota").val().replaceAll('₡ ','').replaceAll(',','');
                let extra = $("#cuota_ex").val().replaceAll('₡ ','').replaceAll(',','');

                //parse
                let p_cuota = parseFloat(cuota);
                let p_extra = parseFloat(extra);

                let resp = 0;

                if(p_cuota >= 1 && p_extra >= 1){
                    resp = p_cuota+p_extra;
                }

                $(this).asignacion_numeric("#p_total",resp)
                return false;
            })
        }

        $.fn.calcular_extra = function() {
            return this.each(function(){

                //cuota extraordinaria calculo
                let cuota = $("#cuota_ex").val().replaceAll('₡ ','').replaceAll(',','');
                let cuota_n1 = $("#n_pagos").val();
                // let cuota_n1 = $("#m_pagar").val();

                //parse
                let p_cuota = parseFloat(cuota);
                let p_cuotan = parseFloat(cuota_n1);

                let resp = 0;

                if(p_cuota >= 1 && p_cuotan >= 1){
                    resp = p_cuota/p_cuotan;
                }

                $(this).asignacion_numeric("#m_pagar",resp)
                return false;
            })
        }




        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_filial',false,() => {
                    $(this).forms_modal({"page" : "propietario_filial","data1" : "<?=$id?>","title" : "Filial"})
                });
                return false;
            }
        })

    })
</script>